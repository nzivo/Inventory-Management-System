<?php

namespace App\Http\Controllers;

use App\Mail\DispatchNoteMail;
use App\Mail\DispatchRequestNotification;
use App\Models\Activity;
use App\Models\Admin\Company;
use App\Models\Assets\SerialNumber;
use App\Models\DispatchRequest;
use App\Models\DispatchRequestSerialNumber;
use App\Models\EmailSubscriber;
use App\Models\Item;
use App\Models\SerialNumberLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert;
use Illuminate\Support\Facades\DB;

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
            'type' => 'required|string|max:255',
        ]);

        // Create the dispatch request
        $dispatchRequest = DispatchRequest::create([
            'user_id' => Auth::id(),
            'approver_id' => null,  // Will be set after approval
            'site' => $request->site,
            'description' => $request->description,
            'status' => 'pending',
            'dispatch_number' => DispatchRequest::generateDispatchNumber(), // Generate unique dispatch number
            'deployment_type' => $request->type,
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
            $dispatchRequest->serialNumbers()->attach($serialNumber->id, [
                'item_id' => $serialNumber->item_id,
            ]);
        }

        // Send the email notification (for low stock or dispatch request)
        // $lowStockItems = DB::table('items')
        // ->join('serial_numbers', 'items.id', '=', 'serial_numbers.item_id')
        // ->join('categories', 'items.category_id', '=', 'categories.id')
        // ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
        // ->leftJoin('subcategories', 'items.subcategory_id', '=', 'subcategories.id')
        // ->select(
        //     'items.name',
        //     'categories.name as category_name',
        //     DB::raw('COALESCE(brands.name, subcategories.name, "Unbranded") as group_name'),
        //     DB::raw('COUNT(serial_numbers.id) as stock')
        // )
        // ->where('serial_numbers.status', 'available')
        // ->groupBy('items.name', 'categories.name', 'group_name')
        // ->orderBy('categories.name')
        // ->orderBy('group_name')
        // ->orderBy('stock', 'asc')
        // ->get();

        $lowStockItems = DB::table('items')
        ->leftJoin('serial_numbers', 'items.id', '=', 'serial_numbers.item_id')
        ->join('categories', 'items.category_id', '=', 'categories.id')
        ->leftJoin('brands', 'items.brand_id', '=', 'brands.id')
        ->leftJoin('subcategories', 'items.subcategory_id', '=', 'subcategories.id')
        ->select(
            'items.name',
            'categories.name as category_name',
            DB::raw('COALESCE(brands.name, subcategories.name, "Unbranded") as group_name'),
            DB::raw("COUNT(CASE WHEN serial_numbers.status = 'available' THEN 1 END) as stock")
        )
        ->groupBy('items.name', 'categories.name', 'group_name')
        ->orderBy('categories.name')
        ->orderBy('group_name')
        ->orderBy('stock', 'asc')
        ->get();

        if ($lowStockItems->isNotEmpty()) {
            Mail::to('mchama@fon.co.ke')
                ->cc('renney@fon.co.ke')
                ->send(new LowStockAlert($lowStockItems));
        }

        // Return to the dispatch request index with a success message
        return redirect()->route('dispatch_requests.index')->with('success', 'Dispatch request created successfully!');
    }

    private function sendDispatchNotification(DispatchRequest $dispatchRequest)
    {
        // Retrieve a list of email subscribers who are subscribed to 'dispatch_request' notifications
        $subscribers = EmailSubscriber::where('type', 'dispatch_request')->get();

        // Loop through each subscriber and send the email notification
        foreach ($subscribers as $subscriber) {
            // Send the dispatch request notification email to each subscriber
            Mail::to($subscriber->email)->send(new DispatchRequestNotification($dispatchRequest));
        }
    }

    public function index()
    {
        // Check if the current user has the 'user' role
        if (auth()->user()->hasRole('user')) {
            // If user has 'user' role, return only their dispatch requests
            $dispatchRequests = DispatchRequest::with('user') // Eager load the 'user' relationship
                ->where([['user_id', auth()->id()],['deployment_type', 'New Deployment']]) // Filter by the currently authenticated user's ID and Deployment Type
                ->orderBy('created_at', 'desc') // Optional: order by creation date
                ->paginate(10); // Paginate with 10 items per page

            return view('inventory.dispatch_requests.user_index', compact('dispatchRequests')); // Adjusted view for users with 'user' role
        } else {
            // Fetch only 'New Deployment' dispatch requests for admins or other roles
            $dispatchRequests = DispatchRequest::with('user')
                ->where('deployment_type', 'New Deployment') // <-- Apply filter here
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('inventory.dispatch_requests.index', compact('dispatchRequests'));
        }

    }

    public function maintenance()
    {
        if (auth()->user()->hasRole('user')) {
            $dispatchRequests = DispatchRequest::with('user')
                ->where([
                    ['user_id', auth()->id()],
                    ['deployment_type', 'Maintenance']
                ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('inventory.dispatch_requests.user_index', compact('dispatchRequests'));
        } else {
            $dispatchRequests = DispatchRequest::with('user')
                ->where('deployment_type', 'Maintenance') // <-- Add this line
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('inventory.dispatch_requests.index', compact('dispatchRequests'));
        }
    }


    public function show($id)
    {
        // Eager load serialNumbers and their associated item
        $dispatchRequest = DispatchRequest::with(['user', 'serialNumbers.item'])->findOrFail($id);

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

    // Destroy or Delete a Dispatch request
    public function destroy($id)
    {
        // Find the dispatch request by ID
        $dispatchRequest = DispatchRequest::find($id);

        // Check if it exists
        if (!$dispatchRequest) {
            return redirect()->back()->with('error', 'Dispatch request not found.');
        }

        // Delete the dispatch request
        $dispatchRequest->delete();

        return redirect()->back()->with('success', 'Dispatch request deleted successfully.');
    }

    // Edit a Dispatch Request
    public function edit($id)
    {
        // Load dispatch request with related serial numbers and items
        $dispatchRequest = DispatchRequest::with(['user', 'serialNumbers.item'])->findOrFail($id);

        // You may also want to pass other needed data (e.g., available serial numbers or users)
        return view('inventory.dispatch_requests.edit', compact('dispatchRequest'));
    }

    public function update(Request $request, $id)
    {
        $dispatch = DispatchRequest::findOrFail($id);

        $dispatch->status = $request->input('status');
        $dispatch->save();

        return redirect()->route('dispatch-requests.index')->with('success', 'Dispatch updated successfully.');
    }


}
