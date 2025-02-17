<?php

namespace App\Http\Controllers;

use App\Mail\DispatchNoteMail;
use App\Models\Activity;
use App\Models\Admin\Company;
use App\Models\Assets\SerialNumber;
use App\Models\DispatchRequest;
use App\Models\DispatchRequestSerialNumber;
use App\Models\Item;
use App\Models\SerialNumberLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispatchRequestController extends Controller
{
    public function create()
    {
        // Get all items with available serial numbers
        $serialNumbers = SerialNumber::where('status', 'available')
            ->with('item') // Get associated item name
            ->get();

        return view('inventory.dispatch_requests.create', compact('serialNumbers'));
    }

    // Store a new dispatch request
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'site' => 'required|string|max:255',
            'serial_numbers' => 'required|array|min:1',
            'serial_numbers.*' => 'exists:serial_numbers,id',
            'description' => 'nullable|string|max:255',
        ]);

        // Create the dispatch request
        $dispatchRequest = DispatchRequest::create([
            'user_id' => Auth::id(),
            'approver_id' => null,  // Will be set after approval
            'site' => $request->site,
            'description' => $request->description,
            'status' => 'pending',
            'dispatch_number' => DispatchRequest::generateDispatchNumber(), // Generate unique dispatch number
        ]);

        // Attach selected serial numbers to the dispatch request
        foreach ($request->serial_numbers as $serialNumberId) {
            // Fetch the serial number by ID
            $serialNumber = SerialNumber::findOrFail($serialNumberId);

            // Ensure the serial number status is 'available' before updating it
            if ($serialNumber->status !== 'available') {
                return redirect()->route('dispatch_requests.create')->with('error', 'Some serial numbers are not available.');
            }

            // Update the status of serial numbers to 'requested'
            $serialNumber->update(['status' => 'requested']);

            // Log the serial number status update in SerialNumberLog
            SerialNumberLog::create([
                'serial_number_id' => $serialNumber->id,
                'user_id' => Auth::id(),
                'description' => 'Updated serial number ' . $serialNumber->serial_number . ' status to requested for dispatch request.',
            ]);

            // Create the relationship in the pivot table (dispatch_request_serial_numbers)
            $dispatchRequest->serialNumbers()->create([
                'serial_number_id' => $serialNumber->id // Store the serial_number's ID, not serial_number value
            ]);
        }

        // Return to the dispatch request index with a success message
        return redirect()->route('dispatch_requests.index')->with('success', 'Dispatch request created successfully!');
    }

    public function index()
    {
        // Check if the current user has the 'user' role
        if (auth()->user()->hasRole('user')) {
            // If user has 'user' role, return only their dispatch requests
            $dispatchRequests = DispatchRequest::with('user') // Eager load the 'user' relationship
                ->where('user_id', auth()->id()) // Filter by the currently authenticated user's ID
                ->orderBy('created_at', 'desc') // Optional: order by creation date
                ->paginate(10); // Paginate with 10 items per page

            return view('inventory.dispatch_requests.user_index', compact('dispatchRequests')); // Adjusted view for users with 'user' role
        } else {
            // Fetch all dispatch requests for admins or other roles
            $dispatchRequests = DispatchRequest::with('user') // Eager load the 'user' relationship
                ->orderBy('created_at', 'desc') // Optional: order by creation date
                ->paginate(10); // Paginate with 10 items per page

            return view('inventory.dispatch_requests.index', compact('dispatchRequests'));
        }
    }


    public function show($id)
    {
        // Eager load serialNumbers and their associated item
        $dispatchRequest = DispatchRequest::with(['user', 'serialNumbers.serialNumber.item'])->findOrFail($id);

        return view('inventory.dispatch_requests.show', compact('dispatchRequest'));
    }


    // Approve or Deny a dispatch request
    public function updateStatus(Request $request, $id)
    {
        // Validate the status input
        $request->validate([
            'status' => 'required|in:approved,denied',
        ]);

        // Find the dispatch request
        $dispatchRequest = DispatchRequest::findOrFail($id);

        // Check if the status is approved and update serial numbers
        if ($request->status === 'approved') {
            // Update the status of the dispatch request
            $dispatchRequest->update([
                'status' => 'approved',
                'approver_id' => Auth::id(), // Assign the approver
            ]);

            Activity::create([
                'user_id' => Auth::id(), // From the request
                'activity' => "Approved dispatch request " . $dispatchRequest->dispatch_number . ".", // From the request
                'status' => "completed",  // From the request
            ]);

            // Update the status of associated serial numbers to "dispatched"
            foreach ($dispatchRequest->serialNumbers as $serialNumberRelation) {
                $serialNumber = $serialNumberRelation->serialNumber;  // Get the actual serial number

                // Update the status of the serial number
                $serialNumber->update([
                    'status' => 'dispatched',
                ]);

                SerialNumberLog::create([
                    'serial_number_id' => $serialNumber->id,
                    'user_id' => auth()->user()->id,
                    'description' => 'Dispatched item',
                ]);
            }
        } else {
            // If the status is not approved, just update it to "denied"
            $dispatchRequest->update([
                'status' => 'denied',
                'approver_id' => Auth::id(),
            ]);

            Activity::create([
                'user_id' => Auth::id(), // From the request
                'activity' => "Denied dispatch request " . $dispatchRequest->dispatch_number . ".", // From the request
                'status' => "completed",  // From the request
            ]);

            // Update the status of associated serial numbers to "available"
            foreach ($dispatchRequest->serialNumbers as $serialNumberRelation) {
                $serialNumber = $serialNumberRelation->serialNumber;  // Get the actual serial number

                // Update the status of the serial number
                $serialNumber->update([
                    'status' => 'available',
                ]);

                SerialNumberLog::create([
                    'serial_number_id' => $serialNumber->id,
                    'user_id' => auth()->user()->id,
                    'description' => 'Not dispatched item made available for request',
                ]);
            }
        }

        // Redirect back with success message
        return redirect()->route('dispatch_requests.index')->with('success', 'Dispatch request ' . $request->status . ' successfully.');
    }

    public function printGatePass($id)
    {
        // Fetch the dispatch request by ID with associated serial numbers
        $dispatchRequest = DispatchRequest::with('serialNumbers')->findOrFail($id);

        // Fetch company details (assuming you have a Company model)
        $company = Company::first();  // Fetch the first company, or adjust this if you need a specific company

        // Return the view with both dispatchRequest and company data
        return view('inventory.dispatch_requests.print_gate_pass', compact('dispatchRequest', 'company'));
    }
}
