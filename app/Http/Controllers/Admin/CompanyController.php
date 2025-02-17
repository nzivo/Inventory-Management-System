<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Admin\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        // Check if a company already exists
        $company = Company::first();  // This will get the first company (if any)
        return view('admin.companies.index', compact('company'));
    }
    // Show the company details
    public function show($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.companies.show', compact('company'));
    }

    // Show the create form
    public function create()
    {
        return view('admin.companies.create');
    }

    // Store the company details
    public function store(Request $request)
    {
        // If a company already exists, prevent creating another one
        if (Company::count() > 0) {
            return redirect()->route('companies.index')
                ->with('error', 'A company already exists.');
        }

        // Validate input
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'postal_address' => 'required',
            'postal_code' => 'required',
            'primary_phone' => 'required',
            'secondary_phone' => 'required',
            'email' => 'required|email',
            'url' => 'nullable|url',
        ]);

        // Create the new company
        Company::create($request->all());
        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Created company", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully.');
    }


    // Show the edit form
    public function edit($id)
    {
        $company = Company::findOrFail($id);
        return view('admin.companies.edit', compact('company'));
    }

    // Update the company details
    public function update(Request $request, $id)
    {
        // Find the company
        $company = Company::findOrFail($id);

        // Validate input
        $request->validate([
            'name' => 'required',
            'location' => 'required',
            'postal_address' => 'required',
            'postal_code' => 'required',
            'primary_phone' => 'required',
            'secondary_phone' => 'required',
            'email' => 'required|email',
            'url' => 'nullable|url',
        ]);

        // Update company details
        $company->update($request->all());

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated company", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Company details updated successfully.');
    }
}
