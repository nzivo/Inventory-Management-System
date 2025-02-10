<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Assets\SerialNumber;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SerialNumberController extends Controller
{
    // Display form to add serial numbers
    public function create(Item $item)
    {
        return view('inventory.assets.create', compact('item'));
    }

    // Store serial numbers in the database
    public function store(Request $request, Item $item)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'serial_numbers' => 'required|array',
            'serial_numbers.*' => 'required|string|unique:serial_numbers,serial_number',
            'quantity' => 'required|integer|min:1',  // Ensure quantity is also validated
        ]);

        // Update the item quantity and available_quantity
        $item->quantity += $validated['quantity']; // Increase total quantity
        $item->available_quantity += $validated['quantity']; // Increase available quantity
        $item->save();

        // Insert the serial numbers into the database
        foreach ($validated['serial_numbers'] as $serialNumber) {
            SerialNumber::create([
                'item_id' => $item->id,
                'serial_number' => $serialNumber,
                'created_by' => Auth::id(),
            ]);
        }

        // Redirect to the assets page with a success message
        return redirect()->route('asset.assets')->with('success', 'Items added successfully');
    }
}
