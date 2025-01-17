<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Logo validation
        ]);

        // Create a new Brand instance
        $brand = new Brand();
        $brand->name = $request->name;
        $brand->created_by = Auth::id();

        // Handling the logo upload
        if ($request->hasFile('logo')) {
            // Get the logo file
            $logo = $request->file('logo');

            // Generate a unique name for the image and store it in 'public/brand_images'
            $logoName = time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('public/brand_images', $logoName);

            // Resize the logo to 500x500
            $resizedLogoPath = public_path('storage/brand_images/' . $logoName);
            $img = Image::make($resizedLogoPath);

            // Resize the image to 500x500 pixels
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // Prevent upscaling smaller images
            });

            // Save the resized logo
            $img->save($resizedLogoPath);

            // Store the logo URL
            $brand->logo = asset('storage/brand_images/' . $logoName);
        }

        // Save the brand to the database
        $brand->save();

        return redirect()->route('brands.index')->with('success', 'Brand created successfully');
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

        return redirect()->route('brands.index')->with('success', 'Brand deleted successfully');
    }
}
