<?php

namespace App\Http\Controllers\Assets;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Assets\SerialNumber;
use App\Models\Category;
use App\Models\Item;
use App\Models\SerialNumberLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SerialNumberController extends Controller
{
    // Display form to add serial numbers
    public function create(Item $item)
    {
        return view('inventory.assets.create', compact('item'));
    }

    // Store serial numbers in the database
    public function store(Request $request, Item $item)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'serial_numbers' => 'required|array',
            'serial_numbers.*' => 'required|string|unique:serial_numbers,serial_number',
            'quantity' => 'required|integer|min:1',  // Ensure quantity is also validated
        ]);

        // Update the item quantity and available_quantity
        $item->quantity += $validated['quantity']; // Increase total quantity
        $item->available_quantity += $validated['quantity']; // Increase available quantity
        $item->save();

        // Insert the serial numbers into the database
        foreach ($validated['serial_numbers'] as $serialNumber) {
            SerialNumber::create([
                'item_id' => $item->id,
                'serial_number' => $serialNumber,
                'created_by' => Auth::id(),
                'category_id' => $item->category_id,
            ]);
        }

        Activity::create([
            'user_id' => Auth::id(), // From the request
            'activity' => "Added serial number(s)", // From the request
            'status' => "completed",  // From the request
        ]);

        // Redirect to the assets page with a success message
        return redirect()->route('asset.assets')->with('success', 'Items added successfully');
    }

    // Method to show all serial numbers belonging to the "Employee Devices" category
    public function employeeDevicesIndex()
    {
        // Assuming you have a relationship between SerialNumber and Category
        $employeeDevicesCategory = Category::where('name', 'Employee Devices')->first();

        if (!$employeeDevicesCategory) {
            return redirect()->route('serialnumbers.index')->with('error', 'Employee Devices category not found.');
        }

        // Get all serial numbers with the specific category
        $serialNumbers = SerialNumber::where('category_id', $employeeDevicesCategory->id)->get();

        return view('employee_devices.employee_devices', compact('serialNumbers'));
    }

    // Method to show the assign form
    public function assignForm(SerialNumber $serialNumber)
    {
        $users = User::all();  // Fetch all users for selection
        return view('employee_devices.assign', compact('serialNumber', 'users'));
    }

    // Method to handle the assignment of a serial number to a user
    public function assign(Request $request, SerialNumber $serialNumber)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Assign the user to the serial number
        $serialNumber->user_id = $request->user_id;
        $serialNumber->status = 'assigned'; // Update the status if needed
        $serialNumber->save();

        // Reduce the available quantity of the item
        $serialNumber->item->available_quantity = $serialNumber->item->available_quantity - 1;
        $serialNumber->item->save();

        // Log the assignment activity
        SerialNumberLog::create([
            'serial_number_id' => $serialNumber->id,
            'user_id' => auth()->user()->id,
            'description' => 'Assigned serial number to user ' . $serialNumber->user->name,
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => "Assigned a serial number",
            'status' => "completed",
        ]);

        return redirect()->route('serialnumbers.employee_devices.index')->with('success', 'Serial number assigned successfully!');
    }


    public function unassignSerialNumber(SerialNumber $serialNumber)
    {
        // Check if the serial number has an assigned user
        if ($serialNumber->user) {
            // Unassign the serial number by setting the user_id to null
            $serialNumber->user_id = null;
            $serialNumber->status = 'available'; // Change status back to available
            $serialNumber->save();

            // Increase the available quantity of the item
            $serialNumber->item->available_quantity = $serialNumber->item->available_quantity + 1;
            $serialNumber->item->save();

            // Log the unassign activity
            SerialNumberLog::create([
                'serial_number_id' => $serialNumber->id,
                'user_id' => auth()->user()->id,
                'description' => 'Unassigned serial number from user ' . $serialNumber->user->name,
            ]);

            Activity::create([
                'user_id' => Auth::id(),
                'activity' => "Unassigned a serial number",
                'status' => "completed",
            ]);

            // Redirect with success message
            return redirect()->route('serialnumbers.employee_devices.index')->with('success', 'Serial number unassigned successfully.');
        }

        Activity::create([
            'user_id' => Auth::id(),
            'activity' => "Unassigned a serial number",
            'status' => "failed",
        ]);

        // If no user was assigned, return an error message
        return redirect()->route('serialnumbers.employee_devices.index')->with('error', 'Serial number is not assigned to any user.');
    }
}
