<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    // Display all designations
    public function index()
    {
        $designations = Designation::all();
        return view('admin.designations.index', compact('designations'));
    }

    // Show form to create a new designation
    public function create()
    {
        return view('admin.designations.create');
    }

    // Store a new designation
    public function store(Request $request)
    {
        $request->validate([
            'designation_name' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
        ]);

        Designation::create($request->all());

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }

    // Show form to edit an existing designation
    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        return view('admin.designations.edit', compact('designation'));
    }

    // Update an existing designation
    public function update(Request $request, $id)
    {
        $request->validate([
            'designation_name' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
        ]);

        $designation = Designation::findOrFail($id);
        $designation->update($request->all());

        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    // Delete an existing designation
    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }
}