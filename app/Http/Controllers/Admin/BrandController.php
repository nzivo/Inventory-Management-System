<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Admin\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    // Show the form for creating a new brand
    public function create()
    {
        return view('admin.brands.create');
    }

    // Store a newly created brand
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Logo validation
        ]);

        // Handle the logo upload
        $logoUrl = null;
        $thumbnailUrl = null;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $destinationPath = public_path('assets/brand_images');

            // Ensure the directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the uploaded logo
            $logo->move($destinationPath, $logoName);
            $logoUrl = asset('assets/brand_images/' . $logoName);

            // Generate and store a thumbnail
            $logoPath = public_path('assets/brand_images/' . $logoName);
            $img = Image::make($logoPath);
            $img->resize(150, 150); // Adjust thumbnail size if needed

            // Ensure thumbnail directory exists
            $thumbnailPath = public_path('assets/brand_images/thumbnails');
            if (!file_exists($thumbnailPath)) {
                mkdir($thumbnailPath, 0777, true);
            }

            // Save the thumbnail
            $thumbnailFilePath = $thumbnailPath . '/' . $logoName;
            $img->save($thumbnailFilePath);
            $thumbnailUrl = asset('assets/brand_images/thumbnails/' . $logoName);
        }

        // Create a new Brand instance
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->created_by = Auth::id();
        $brand->logo = $logoUrl;
        // $brand->thumbnail = $thumbnailUrl; // Store the thumbnail URL
        $brand->save();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Created brand", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
    }


    // Display all brands
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    // Show the form for editing the specified brand
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brands.edit', compact('brand'));
    }

    // Update the specified brand
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Logo validation
        ]);

        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        $brand->updated_by = Auth::id();

        // Handle the logo upload (if a new logo is provided)
        if ($request->hasFile('logo')) {
            // Delete the old logo file if it exists
            if ($brand->logo) {
                $oldLogoPath = str_replace(asset('storage/'), 'public/', $brand->logo);
                \Storage::delete($oldLogoPath);  // Delete old logo file
            }

            // Store the original logo
            $logo = $request->file('logo');
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('assets/brand_images', $logoName, 'public');
            $brand->logo = asset('storage/' . $logoPath);

            // Resize the logo to 500x500 pixels and save
            $resizedLogoPath = public_path('storage/assets/brand_images/' . $logoName);
            $img = Image::make($resizedLogoPath);
            $img->resize(500, 500);  // Resize to 500x500
            $img->save($resizedLogoPath);  // Save resized image back
        }

        $brand->save();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated branch", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully');
    }

    // Delete the specified brand
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        // Delete the logo file from storage
        if ($brand->logo) {
            $logoPath = str_replace(asset('storage/'), 'public/', $brand->logo);
            \Storage::delete($logoPath); // Delete logo file from storage
        }

        $brand->delete();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Deleted branch", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully');
    }
}
