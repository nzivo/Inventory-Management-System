<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::with('creator')->get();  // Assuming you have a `creator` relationship
        return view('admin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.branches.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required',
            'location' => 'required',
        ]);

        if (Auth::check()) {
            Branch::create([
                'name' => $request->name,
                'location' => $request->location,
                'created_by' => Auth::id(), // This will work if the user is authenticated
            ]);
            Activity::create([
                'user_id' => Auth::id(), // From the request
                'activity' => "Created branch", // From the request
                'status' => "completed",  // From the request
            ]);
        } else {
            return redirect()->route('login')->with('error', 'You must be logged in to create a branch.');
        }

        // Redirect with success message
        return redirect()->route('branches.index')->with('success', 'Branch created successfully!');
    }

    public function show($id)
    {
        $branch = Branch::with('creator')->findOrFail($id);  // Get the branch with creator details
        return view('admin.branches.show', compact('branch'));
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.branches.edit', compact('branch'));
    }

    // Update the branch in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update([
            'name' => $request->input('name'),
            'location' => $request->input('location'),
            'updated_by' => auth()->id(), // Set updated_by to the current logged-in user
        ]);

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated branch", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully!');
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);

        // Optionally check if the branch is being used elsewhere, if needed
        // For example, if there are related records, you may want to prevent deletion

        $branch->delete();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Deleted branch", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully!');
    }
}
