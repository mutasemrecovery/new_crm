<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        // Filter by activation status
        if ($request->has('activate') && $request->activate != '') {
            $query->where('activate', $request->activate);
        }

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'activate' => 'required|in:1,2',
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        User::create($data);

        return redirect()->route('users.index')
            ->with('success', __('messages.User created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'activate' => 'required|in:1,2',
        ]);

        $data = $request->except('password');

        // Handle password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = uploadImage('assets/admin/uploads', $request->photo);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', __('messages.User updated successfully'));
    }



}
