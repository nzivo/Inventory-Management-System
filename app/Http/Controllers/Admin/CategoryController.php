<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('creator')->get();  // Assuming you have a `creator` relationship
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (Auth::check()) {
            Category::create([
                'name' => $request->name,
                'created_by' => Auth::id(), // This will work if the user is authenticated
            ]);
            Activity::create([
                'user_id' => Auth::id(), // From the request
                'activity' => "Created category", // From the request
                'status' => "completed",  // From the request
            ]);
        } else {
            return redirect()->route('login')->with('error', 'You must be logged in to create a category.');
        }

        // Redirect with success message
        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    public function show($id)
    {
        $category = Category::with('creator')->findOrFail($id);  // Get the category with creator details
        return view('admin.categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    // Update the category in the database
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->input('name'),
            'updated_by' => auth()->id(), // Set updated_by to the current logged-in user
        ]);

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated category", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        // Optionally check if the category is being used elsewhere, if needed
        // For example, if there are related records, you may want to prevent deletion

        $category->delete();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Deleted category", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }
}
