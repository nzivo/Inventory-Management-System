<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Created a permission", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Updated a permission", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Deleted a permission", // From the request
            'status' => "completed",  // From the request
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }
}
