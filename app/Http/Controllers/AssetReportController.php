<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;
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
        $dispatched = (clone $query)->where('store_status', 'dispatched')->count();
        $inStore = (clone $query)->where('store_status', 'in_store')->count();

        // Get asset counts by name
        $byType = (clone $query)
            ->where('store_status', 'in_store')
            ->selectRaw('name, COUNT(*) as count')
            ->groupBy('name')
            ->get();

        // Notify admin if low stock
        foreach ($byType as $asset) {
            if ($asset->count < 5) {
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
                        SendLowStockNotification::dispatch($asset)->delay(now()->addSeconds(5)); // delay to reduce burst
                    }
                }
            }
        }

        return view('reports.assets', compact('total', 'dispatched', 'inStore', 'byType'));
    }
}

