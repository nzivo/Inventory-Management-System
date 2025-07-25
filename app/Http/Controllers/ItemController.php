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
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

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
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'branch_id' => 'required|exists:branches,id',
            'quantity' => 'required|integer|min:1',
            'serial_numbers' => 'required|array|min:1',
            'serial_numbers.*' => 'required|string|unique:serial_numbers,serial_number',
            // 'serial_numbers' => 'nullable|array',
            // 'serial_numbers.*' => 'nullable|string|distinct|unique:serial_numbers,serial_number',
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
                Log::error("Error saving thumbnail: " . $e->getMessage());
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
            Log::info('Creating serial', ['item_id' => $item->id, 'serial_number' => $serialNumber]);

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
        $subcategories = Subcategory::all();
        $branches = Branch::all();

        return view('inventory.assets.edit', compact('item', 'categories', 'branches', 'subcategories'));
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
            'subcategory_id' => 'required|exists:subcategories,id',
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
            'subcategory_id' => $request->subcategory_id,
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

    public function storeBasic(Request $request)
    {
        Log::info('Received storeBasic request.', $request->all());

        // 1. Validate
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:subcategories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'branch_id' => 'required|exists:branches,id',
                'quantity' => 'required|integer|min:1',
                'item_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            Log::info('Validation passed.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed.', $e->errors());
        return back()->withErrors($e->errors())->withInput();
    }



        $userId = Auth::id();
        Log::info('User ID fetched', ['user_id' => $userId]);

        $imageUrl = null;
        $thumbnailUrl = null;

        // 2. Handle image upload
        if ($request->hasFile('item_img')) {
            try {
                Log::info('Image upload started');
                $image = $request->file('item_img');
                $imageName = time() . '.' . $image->getClientOriginalExtension();

                $imagePath = public_path('assets/item_images');
                $thumbPath = $imagePath . '/thumbnails';

                // Ensure directories exist
                if (!file_exists($imagePath)) mkdir($imagePath, 0777, true);
                if (!file_exists($thumbPath)) mkdir($thumbPath, 0777, true);

                // Move main image
                $image->move($imagePath, $imageName);
                $imageUrl = asset("assets/item_images/{$imageName}");
                Log::info("Main image saved at: $imageUrl");

                // Generate thumbnail
                $img = Image::make($imagePath . '/' . $imageName);
                $img->resize(150, 150)->save("{$thumbPath}/{$imageName}");
                $thumbnailUrl = asset("assets/item_images/thumbnails/{$imageName}");
                Log::info("Thumbnail saved at: $thumbnailUrl");

            } catch (\Exception $e) {
                Log::error('Image upload or thumbnail failed: ' . $e->getMessage());
                return back()->with('error', 'Image upload or thumbnail failed.')->withInput();
            }
        }

        // 3. Create item
        try {
            $item = Item::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'] ?? null,
                'category_id' => $validatedData['category_id'],
                'subcategory_id' => $validatedData['subcategory_id'] ?? null,
                'brand_id' => $validatedData['brand_id'] ?? null,
                'supplier_id' => $validatedData['supplier_id'] ?? null,
                'branch_id' => $validatedData['branch_id'],
                'created_by' => $userId,
                'item_img' => $imageUrl,
                'thumbnail_img' => $thumbnailUrl,
                'quantity' => $validatedData['quantity'],
                'threshold' => 1,
                'available_quantity' => $validatedData['quantity'],
            ]);

            Log::info("Item created successfully with ID: {$item->id}");
        } catch (\Exception $e) {
            Log::error('Failed to create item: ' . $e->getMessage());
            return back()->with('error', 'Failed to create item.')->withInput();
        }

        // 4. Log user activity
        try {
            Activity::create([
                'user_id' => $userId,
                'activity' => "Created item: {$validatedData['name']} without serial numbers",
                'status' => "completed",
            ]);
            Log::info("Activity logged for item creation.");
        } catch (\Exception $e) {
            Log::warning("Activity logging failed: " . $e->getMessage());
        }

        return redirect()->route('items.index')->with('success', 'Item created successfully without serial numbers.');
    }

    public function createBasic()
    {

        $categories = Category::all();
        $subcategories = Subcategory::all();
        $brands = Brand::all();
        $suppliers = Supplier::all();
        $branches = Branch::all();

        return view('items.create_basic', compact('categories', 'subcategories', 'brands', 'suppliers', 'branches'));
    }

    public function editSerials()
    {
        Log::info('Fetching items without serial numbers');
        $items = Item::doesntHave('serialNumbers')->get();
        return view('items.add_serials', compact('items'));
    }

    public function updateSerials(Request $request)
    {
        foreach ($request->input('serials', []) as $itemId => $serial) {
            $serial = trim($serial);
            $shouldGenerate = $request->has("generate.$itemId");

            // Check if serial already exists for this item
            $existing = SerialNumber::where('item_id', $itemId)->first();

            if ($existing) {
                $existing->serial_number = $serial ?: ($shouldGenerate ? $this->generateSerial($itemId) : $existing->serial_number);
                $existing->save();
            } else {
                $newSerial = $serial ?: ($shouldGenerate ? $this->generateSerial($itemId) : null);
                if ($newSerial) {
                    SerialNumber::create([
                        'item_id' => $itemId,
                        'serial_number' => $newSerial,
                        'created_by' => Auth::id(),
                    ]);
                }
            }
        }

        return back()->with('success', 'Serial numbers updated successfully.');
    }

    private function generateSerial($itemId)
    {
        $prefix = 'SN' . str_pad($itemId, 4, '0', STR_PAD_LEFT);
        $random = strtoupper(Str::random(5)); // Ensure `use Illuminate\Support\Str;`
        return $prefix . '-' . $random;
    }

}
