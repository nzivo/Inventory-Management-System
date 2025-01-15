<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCategoryController extends Controller
{
    public function index()
    {
        // Get all subcategories with their associated categories
        $subcategories = SubCategory::with('category', 'createdBy', 'updatedBy')->get();
        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        // Get all categories to show in the create form
        $categories = Category::all();
        return view('admin.subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure category exists
        ]);

        if (Auth::check()) {
            // Create the subcategory with the authenticated user as the creator
            SubCategory::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'created_by' => Auth::id(),
            ]);
        } else {
            return redirect()->route('login')->with('error', 'You must be logged in to create a subcategory.');
        }

        // Redirect with success message
        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully!');
    }

    public function show($id)
    {
        // Get the subcategory with its category and creator information
        $subcategory = SubCategory::with('category', 'createdBy', 'updatedBy')->findOrFail($id);
        return view('admin.subcategories.show', compact('subcategory'));
    }

    public function edit($id)
    {
        // Get the subcategory to be edited, along with all categories
        $subcategory = SubCategory::findOrFail($id);
        $categories = Category::all();
        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Ensure category exists
        ]);

        // Find and update the subcategory
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update([
            'name' => $request->input('name'),
            'category_id' => $request->input('category_id'),
            'updated_by' => auth()->id(),
        ]);

        // Redirect with success message
        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully!');
    }

    public function destroy($id)
    {
        // Find the subcategory and delete it
        $subcategory = SubCategory::findOrFail($id);

        // Optionally check if the subcategory is being used elsewhere
        // For example, if there are related records, you may want to prevent deletion

        $subcategory->delete();

        // Redirect with success message
        return redirect()->route('subcategories.index')->with('success', 'Subcategory deleted successfully!');
    }
}
