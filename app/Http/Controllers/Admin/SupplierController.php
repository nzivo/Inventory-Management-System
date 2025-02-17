<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Admin\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    // Display all suppliers
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    // Show form to create a new supplier
    public function create()
    {
        return view('admin.suppliers.create');
    }

    // Store a new supplier
    public function store(Request $request)
    {
        // Validate input fields except for 'created_by'
        $validated = $request->validate([
            'name' => 'required',
            'location' => 'nullable|string',
            'postal_address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'primary_phone' => 'nullable|string',
            'secondary_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'url' => 'nullable|url',
        ]);

        // Add 'created_by' field manually, as it is not part of the user input
        $validated['created_by'] = Auth::id();

        // Create the new supplier
        Supplier::create($validated);

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Created supplier", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully');
    }


    // Show form to edit an existing supplier
    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('supplier'));
    }

    // Update an existing supplier
    public function update(Request $request, $id)
    {
        // Validate the input fields except for 'updated_by'
        $validated = $request->validate([
            'name' => 'required',
            'location' => 'nullable|string',
            'postal_address' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'primary_phone' => 'nullable|string',
            'secondary_phone' => 'nullable|string',
            'email' => 'nullable|email',
            'url' => 'nullable|url',
        ]);

        // Add 'updated_by' field manually
        $validated['updated_by'] = Auth::id();

        // Find the supplier and update it with validated data
        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated supplier", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    }


    // Delete a supplier
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Deleted supplier", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }
}
