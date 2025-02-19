<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Admin\Brand;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Supplier;
use App\Models\Assets\SerialNumber;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Item;
use App\Models\SerialNumberLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ItemController extends Controller
{

    // Display all items
    // Display all items in a DataTable with search and export functionality
    public function index(Request $request)
    {
        // Get all items with their related data (serialNumbers, category, branch, and creator)
        $items = Item::with(['serialNumbers', 'category', 'branch', 'creator'])->latest()->get();

        return view('items.index', compact('items'));
    }

    public function assets(Request $request)
    {
        // Get all items with their related data (serialNumbers, category, branch, and creator)
        $items = Item::with(['serialNumbers', 'category', 'branch', 'creator'])->latest()->get();

        return view('inventory.assets.index', compact('items'));
    }

    public function show($id, Request $request)
    {
        // Find the item by its ID, including serial numbers and their logs
        $item = Item::with(['serialNumbers.serialNumberLogs.user', 'category', 'branch', 'creator'])->findOrFail($id);

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
        $subcategories = SubCategory::all();  // Fetch subcategories
        $brands = Brand::all();  // Fetch brands
        $suppliers = Supplier::all();  // Fetch suppliers

        return view('items.create', compact('categories', 'branches', 'subcategories', 'brands', 'suppliers'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string ',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
            'serial_numbers' => 'required|array|min:1',
            'serial_numbers.*' => 'required|string|unique:serial_numbers,serial_number',
            'item_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle the image upload
        $imageUrl = null;
        $thumbnailUrl = null;
        if ($request->hasFile('item_img')) {
            $image = $request->file('item_img');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/item_images');

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the uploaded image
            $image->move($destinationPath, $imageName);
            $imageUrl = asset('assets/item_images/' . $imageName);

            // Ensure the thumbnails directory exists
            $thumbnailPath = public_path('assets/item_images/thumbnails');
            if (!file_exists($thumbnailPath)) {
                mkdir($thumbnailPath, 0777, true);
            }

            // Generate thumbnail
            try {
                $imagePath = public_path('assets/item_images/' . $imageName);
                $img = Image::make($imagePath);
                $img->resize(150, 150); // Adjust thumbnail dimensions as needed
                $img->save(public_path('assets/item_images/thumbnails/' . $imageName));
                $thumbnailUrl = asset('assets/item_images/thumbnails/' . $imageName);
            } catch (\Exception $e) {
                \Log::error("Error saving thumbnail: " . $e->getMessage());
                return back()->with('error', 'There was an issue saving the thumbnail image.');
            }
        }

        // Create the item
        $item = Item::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'brand_id' => $request->brand_id,
            'supplier_id' => $request->supplier_id,
            'branch_id' => $request->branch_id,
            'created_by' => Auth::id(),
            'item_img' => $imageUrl,
            'thumbnail_img' => $thumbnailUrl,
            'quantity' => $request->quantity,
            'threshold' => 1,
            'available_quantity' => $request->quantity,
        ]);

        // Create the serial numbers and log the creation
        foreach ($request->serial_numbers as $serialNumber) {
            $serial = SerialNumber::create([
                'item_id' => $item->id,
                'serial_number' => $serialNumber,
                'created_by' => Auth::id(),
                'category_id' => $request->category_id,
            ]);

            // Log the creation activity
            SerialNumberLog::create([
                'serial_number_id' => $serial->id,
                'user_id' => Auth::id(),
                'description' => 'Created serial number ' . $serial->serial_number . ' by ' . auth()->user()->name,
            ]);
        }

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Created " . $request->name . ".", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully with ' . count($request->serial_numbers) . ' serial numbers.');
    }

    // Show edit form
    public function edit(Item $item)
    {
        $categories = Category::all();
        $branches = Branch::all();
        $subcategories = SubCategory::all();  // Fetch subcategories
        $brands = Brand::all();  // Fetch brands
        $suppliers = Supplier::all();  // Fetch suppliers

        return view('items.edit', compact('item', 'categories', 'branches', 'subcategories', 'brands', 'suppliers'));
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
                'updated_by' => Auth::id(),
            ]);
        } else {
            // If no serial number exists, create a new one
            SerialNumber::create([
                'item_id' => $item->id,
                'serial_number' => $serialNumber,
                'created_by' => Auth::id(),
            ]);
        }

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated " . $request->name . ".", // From the request
            'status' => "completed",  // From the request
        ]);

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

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated " . $item->name . " status.", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('items.index')->with('status', 'Item status updated successfully');
    }

    // View a single asset
    public function showAsset($id)
    {
        $item = Item::with([
            'serialNumbers',
            'category',
            'branch',
            'creator'
        ])->findOrFail($id);
        return view('inventory.assets.show', compact('item'));
    }

    // Edit an asset
    public function editAsset($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        $branches = Branch::all();

        return view('inventory.assets.edit', compact('item', 'categories', 'branches'));
    }

    // Update an asset
    public function updateAsset(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'status' => 'required|in:active,inactive',
            'inventory_status' => 'required|in:in_stock,out_of_stock',
        ]);

        // Debugging logs
        Log::info('Updating Asset:', $request->all());

        // Assign values and save
        $item->update([
            'name' => $request->name,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'branch_id' => $request->branch_id,
            'status' => $request->status,
            'inventory_status' => $request->inventory_status,
        ]);

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated " . $request->name . ".", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('asset.assets')->with('success', 'Asset updated successfully');
    }


    // Delete an asset
    public function destroyAsset($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Deleted " . $item->name . ".", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('asset.assets')->with('success', 'Asset deleted successfully');
    }
}
