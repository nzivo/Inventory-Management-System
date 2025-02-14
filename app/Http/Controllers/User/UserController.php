<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\UserCredentialsMail;
use App\Models\Designation;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function profile()
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Pass the user details to the view
        return view('user.index', compact('user'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $data = User::latest()->get();  // Retrieve all users without pagination

        return view('admin.users.index', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();

        // Retrieve full Designation records, not just plucked values
        $designations = Designation::select('id', 'designation_name', 'department_name')->get();

        $generatedPassword = Str::random(12); // or any length you prefer

        return view('admin.users.create', compact('roles', 'designations', 'generatedPassword'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'designation_id' => 'required',
            'status' => 'required',
        ]);

        // Extract the username from the email (before @ symbol)
        $email = $request->input('email');
        $username = strtok($email, '@');  // Get the part before the @ symbol

        // Check if a user with that username already exists, and if so, add an incremental number
        $originalUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;  // Append a number to make it unique
            $counter++;
        }

        // Prepare the user data
        $input = $request->all();
        $input['username'] = $username;  // Set the unique username
        $password = $input['password'];  // Keep the raw password to send in the email
        $input['password'] = Hash::make($input['password']);  // Hash the password

        // Create the user
        $user = User::create($input);

        // Assign roles
        $user->assignRole($request->input('roles'));

        // Send the credentials email
        Mail::to($user->email)->send(new UserCredentialsMail($user, $password));

        // Redirect with success message
        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'status' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    public function updatePassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'password' => 'required|string|min:8',
            'newpassword' => 'required|string|min:8|confirmed',
        ]);

        // Get the current user
        $user = Auth::user();

        // Check if the current password is correct
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->newpassword);
        $user->save();

        // Redirect or respond with a success message
        return back()->with('success', 'Password updated successfully.');
    }
}
