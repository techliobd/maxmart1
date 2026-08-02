<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)->where('is_staff', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $staff = $query->latest()->paginate(20);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff,manager',
            'permissions' => 'nullable|array',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        $data['password'] = Hash::make($request->password);
        $data['is_staff'] = true;
        $data['is_admin'] = $request->role === 'admin';

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('staff', 'public');
        }

        $user = User::create($data);

        // Store permissions if provided
        if ($request->filled('permissions')) {
            $user->update(['permissions' => json_encode($request->permissions)]);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff member created successfully.');
    }

    public function show(User $staff)
    {
        if (!$staff->is_staff && !$staff->is_admin) {
            abort(404);
        }

        return view('admin.staff.show', compact('staff'));
    }

    public function edit(User $staff)
    {
        if (!$staff->is_staff && !$staff->is_admin) {
            abort(404);
        }

        return view('admin.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        if (!$staff->is_staff && !$staff->is_admin) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staff->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,staff,manager',
            'permissions' => 'nullable|array',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone']);
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $data['is_staff'] = true;
        $data['is_admin'] = $request->role === 'admin';

        if ($request->hasFile('avatar')) {
            if ($staff->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($staff->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('staff', 'public');
        }

        $staff->update($data);

        // Update permissions
        if ($request->filled('permissions')) {
            $staff->update(['permissions' => json_encode($request->permissions)]);
        }

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function destroy(User $staff)
    {
        if (!$staff->is_staff && !$staff->is_admin) {
            abort(404);
        }

        // Prevent deleting yourself
        if ($staff->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        if ($staff->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($staff->avatar);
        }

        $staff->delete();

        return redirect()->route('admin.staff.index')->with('success', 'Staff member deleted successfully.');
    }

    public function toggleStatus(User $staff)
    {
        if (!$staff->is_staff && !$staff->is_admin) {
            abort(404);
        }

        $staff->update(['is_active' => !$staff->is_active]);

        return back()->with('success', 'Staff status updated successfully.');
    }
}
