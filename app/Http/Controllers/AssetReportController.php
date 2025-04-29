<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemLog;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\RateLimiter;
use App\Jobs\SendLowStockNotification;

class AssetReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $total = $query->count();
        $lowStock = (clone $query)->whereColumn('available_quantity', '<', 'threshold')->count();
        $inStock = (clone $query)->whereColumn('available_quantity', '>=', 'threshold')->count();

        $byType = (clone $query)
            ->selectRaw('id, name, SUM(quantity) as total_quantity, SUM(available_quantity) as total_available, SUM(quantity - available_quantity) as dispatched, MIN(threshold) as threshold')
            ->groupBy('id', 'name')
            ->get();

        $dispatched = (clone $query)->selectRaw('SUM(quantity - available_quantity) as dispatched')->value('dispatched');

        foreach ($byType as $asset) {
            if ($asset->total_available < $asset->threshold) {
                $admin = User::where('is_admin', true)->first();
                $throttleKey = 'low-stock-notify:' . $asset->name;

                if (RateLimiter::remaining($throttleKey, 1) > 0) {
                    $existing = $admin->notifications()
                        ->where('type', LowStockNotification::class)
                        ->whereJsonContains('data->asset', $asset->name)
                        ->where('created_at', '>=', now()->subDay())
                        ->exists();

                    if (!$existing) {
                        RateLimiter::hit($throttleKey, 3600); // 1 hour
                        SendLowStockNotification::dispatch($asset)->delay(now()->addSeconds(5));
                    }
                }
            }
        }

        return view('reports.assets', compact('total', 'lowStock', 'inStock', 'byType', 'dispatched'));
    }

    public function dispatch(Request $request, Item $item)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $dispatchQty = min($validated['quantity'], $item->available_quantity);

        if ($dispatchQty <= 0) {
            return back()->withErrors(['quantity' => 'No available stock to dispatch.']);
        }

        $item->available_quantity -= $dispatchQty;

        if ($item->available_quantity === 0) {
            $item->store_status = 'dispatched';
        }

        $item->save();

        ItemLog::create([
            'item_id' => $item->id,
            'action' => 'dispatched',
            'quantity' => $dispatchQty,
            'note' => $validated['note'] ?? null,
            'logged_at' => now()
        ]);

        return redirect()->route('reports.assets')->with('success', "Dispatched $dispatchQty units of {$item->name}.");
    }
}



