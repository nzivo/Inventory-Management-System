<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Assets\SerialNumber;
use App\Models\DispatchRequest;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalItems = SerialNumber::count();
        $users = User::count();
        $dispatch_requests = DispatchRequest::count();

        // Get the 5 most recent activities for the currently logged-in user
        $activities = Activity::where('user_id', auth()->id())
            ->latest() // Order by latest (created_at)
            ->limit(5) // Get only 5 activities
            ->get();

        // Pass the activities to the view
        return view('home', compact('totalItems', 'users', 'dispatch_requests', 'activities'));
    }
}
