<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
     // Display a listing of maintenance records
    public function index()
    {
        $maintenances = Maintenance::all();
        return view('maintenance.index', compact('maintenances'));
    }

    // Show the form for creating a new maintenance record
    public function create()
    {
        $assets = Asset::all();  // Fetch all assets to associate with the maintenance
        return view('maintenance.create', compact('assets'));
    }

    // Store a newly created maintenance record
    public function store(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'service_date' => 'required|date',
            'service_type' => 'required|string',
            'technician' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Maintenance::create($request->all());

        return redirect()->route('maintenance.index');
    }

    // Show the form for editing the specified maintenance record
    public function edit($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $assets = Asset::all();
        return view('maintenance.edit', compact('maintenance', 'assets'));
    }

    // Update the specified maintenance record
    public function update(Request $request, $id)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'service_date' => 'required|date',
            'service_type' => 'required|string',
            'technician' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $maintenance = Maintenance::findOrFail($id);
        $maintenance->update($request->all());

        return redirect()->route('maintenance.index');
    }

    // Remove the specified maintenance record
    public function destroy($id)
    {
        Maintenance::destroy($id);

        return redirect()->route('maintenance.index');
    }
}
