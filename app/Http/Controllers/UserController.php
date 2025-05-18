<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Allergy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest; // Optional: use a form request for validation

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve users with pagination (can also eager load allergies if needed)
        $users = User::with('allergies')->get();  // Adjust pagination count as needed
        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $allergies = Allergy::all();
        return view('admin.user.create', compact('allergies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation rules for creating a new user
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum 8 characters
                'confirmed', // Confirm password match
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                // Optionally, include a special character rule
                'regex:/[@$!%*?&]/', // At least one special character (e.g. !, @, $, etc.)
            ],
            'role' => 'required|in:admin,user',
            'allergies' => 'nullable|array',
            'allergies.*' => 'exists:allergies,id',  // Make sure allergies are valid
        ]);

        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->route('users.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Create the new user if validation passes
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),  // Encrypt the password
            'role' => $request->role,
        ]);

        // Attach the selected allergies to the user (using pivot table)
        if ($request->has('allergies')) {
            $user->allergies()->sync($request->allergies);  // Sync allergies with the user
        }

        // Redirect with success message
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve user by ID and load allergies
        $user = User::with('allergies', 'orders')->findOrFail($id);
        return view('admin.user.details', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Pass allergies and user data to the edit view
        $allergies = Allergy::all();
        return view('admin.user.edit', compact('user', 'allergies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'role' => 'required|in:user,admin',
            'allergies' => 'array',
            'allergies.*' => 'integer|exists:allergies,id',
        ]);

        // dd($validatedData);

        // Find the user to be updated
        $user = User::findOrFail($id);

        // Hash the password if provided
        // Hash the password if provided, otherwise remove it from update data
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']); // prevent null update
        }


        // Update the user
        $user->update($validatedData);

        // Sync allergies (this removes old allergies and attaches new ones)
        if ($request->has('allergies')) {
            $user->allergies()->sync($request->allergies);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id == Auth::id()) {
            return redirect()->back()
                ->with('error', 'You cannot delete your own account!');
        }

        // Check if user is the last admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Cannot delete the last administrator!');
        }

        if ($user->isDelivery() && User::where('role', 'delivery')->count() <= 1) {
            return redirect()->back()
                ->with('error', 'Cannot delete the last delivery person!');
        }
         $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}
