<?php

namespace App\Http\Controllers;

use App\Models\Assets\SerialNumber;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class ItemController extends Controller
{

    // Display all items
    // Display all items in a DataTable with search and export functionality
    public function index(Request $request)
    {
        // Get all items with their related data (serialNumbers, category, branch, and creator)
        $items = Item::with(['serialNumbers', 'category', 'branch', 'creator'])->get();

        return view('items.index', compact('items'));
    }

    public function show($id, Request $request)
    {
        // Find the item by its ID
        $item = Item::with(['serialNumbers', 'category', 'branch', 'creator'])->findOrFail($id);

        // If serial_id is provided, get the corresponding serial number
        $serialNumber = null;
        if ($request->has('serial_id')) {
            $serialNumber = $item->serialNumbers()->where('id', $request->serial_id)->first();
        }

        // Return the view with the item and its serial number (if found)
        return view('items.show', compact('item', 'serialNumber'));
    }



    public function create()
    {
        $categories = Category::all();
        $branches = Branch::all();
        return view('items.create', compact('categories', 'branches'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
            'serial_numbers' => 'required|array|min:1',
            'serial_numbers.*' => 'required|string|unique:serial_numbers,serial_number',
            'item_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the image upload
        $imageUrl = null;
        if ($request->hasFile('item_img')) {
            $image = $request->file('item_img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/item_images');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $image->move($destinationPath, $imageName);
            $imageUrl = asset('assets/item_images/' . $imageName);

            // Generate thumbnail
            $imagePath = public_path('assets/item_images/' . $imageName);
            $img = Image::make($imagePath);
            $img->resize(150, 150); // Adjust thumbnail dimensions as needed
            $img->save(public_path('assets/item_images/thumbnails/' . $imageName));
            $thumbnailUrl = asset('assets/item_images/thumbnails/' . $imageName);
        }

        // Create the item
        $item = Item::create([
            'name' => $request->name,
            'description' => $request->type,
            'category_id' => $request->category_id,
            'branch_id' => $request->branch_id,
            'created_by' => Auth::id(),
            'status' => 'in_stock',
            'item_img' => $imageUrl,
            'thumbnail_img' => $thumbnailUrl, // Store the thumbnail URL
        ]);

        // Create the serial numbers
        foreach ($request->serial_numbers as $serialNumber) {
            SerialNumber::create([
                'item_id' => $item->id,
                'serial_number' => $serialNumber,
                'status' => 'in_stock',
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Item created successfully with ' . count($request->serial_numbers) . ' serial numbers.');
    }

    // Show edit form
    public function edit(Item $item)
    {
        $categories = Category::all();
        $branches = Branch::all();
        return view('items.edit', compact('item', 'categories', 'branches'));
    }

    // Update item and serial numbers
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $existingSerialNumbers = $item->serialNumbers->pluck('serial_number')->toArray();

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'serial_number' => 'required|string|unique:serial_numbers,serial_number,' . $id,
        ]);

        // Update the item details
        $item->update([
            'name' => $request->name,
            'description' => $request->type,
            'category_id' => $request->category_id,
            'branch_id' => $request->branch_id,
        ]);

        $serialNumber = $request->input('serial_number');

        $existingSerial = $item->serialNumbers->first();

        if ($existingSerial) {
            // If serial number exists, update it
            $existingSerial->update([
                'serial_number' => $serialNumber,
                'status' => 'in_stock',
                'updated_by' => Auth::id(),
            ]);
        } else {
            // If no serial number exists, create a new one
            SerialNumber::create([
                'item_id' => $item->id,
                'serial_number' => $serialNumber,
                'status' => 'in_stock',
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Item and serial number updated successfully!');
    }





    // Method to update item status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:in_use,under_repair,spoiled,discontinued,discarded',
        ]);

        $item = Item::findOrFail($id);
        $item->status = $request->status;
        $item->save();

        return redirect()->route('items.index')->with('status', 'Item status updated successfully');
    }
}