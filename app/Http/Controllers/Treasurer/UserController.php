<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\UserOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of users for the current organization.
     */
    public function index(Request $request)
    {
        $organizationId = selected_org()->id;

        $filters = $request->only(['search', 'role']);

        $query = User::with(['roles', 'organizations'])
            ->whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId));

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
        }

        $users = $query->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        // Get available roles (excluding admin)
        $roles = Role::where('name', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return Inertia::render('Treasurer/UsersPage', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return Inertia::render('Treasurer/UsersPage', [
            'roles' => $roles,
            'showCreateModal' => true,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $organizationId = selected_org()->id;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        // Check if user already exists
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            // Check if user is already in this organization
            $userInOrg = UserOrganization::where('user_id', $existingUser->id)
                ->where('organization_id', $organizationId)
                ->first();

            if ($userInOrg) {
                return back()->withErrors(['email' => 'This user is already a member of this organization.']);
            }

            // Ask for confirmation to add existing user to organization
            if (!$request->has('confirm_add_existing')) {
                return back()->withInput()->with('confirm_existing_user', [
                    'user' => $existingUser,
                    'role' => $request->role
                ]);
            }

            // Add existing user to organization with role
            $existingUser->organizations()->attach($organizationId);
            $existingUser->addRole($request->role);

            return redirect()->route('treasurer.users.index')
                ->with('success', 'Existing user added to organization successfully.');
        }

        // Create new user
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Add user to organization
            $user->organizations()->attach($organizationId);

            // Assign role
            $user->addRole($request->role);

            return redirect()->route('treasurer.users.index')
                ->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create user.']);
        }
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Request $request, $id)
    {
        $organizationId = selected_org()->id;

        $user = User::with(['roles', 'organizations'])
            ->whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))
            ->find($id);

        if (!$user) {
            return redirect()->route('treasurer.users.index')
                ->with('error', 'User not found.');
        }

        $roles = Role::where('name', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return Inertia::render('Treasurer/UsersPage', [
            'roles' => $roles,
            'editingUser' => $user,
            'showEditModal' => true,
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        $organizationId = selected_org()->id;

        $user = User::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))
            ->find($id);

        if (!$user) {
            return redirect()->route('treasurer.users.index')
                ->with('error', 'User not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|exists:roles,name',
        ]);

        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update password if provided
            if ($request->filled('password')) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            // Update role
            $user->syncRoles([$request->role]);

            return redirect()->route('treasurer.users.index')
                ->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update user.']);
        }
    }

    /**
     * Remove the specified user from the organization.
     */
    public function destroy($id)
    {
        $organizationId = selected_org()->id;

        $user = User::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))
            ->find($id);

        if (!$user) {
            return redirect()->route('treasurer.users.index')
                ->with('error', 'User not found.');
        }

        // Prevent removing the last treasurer
        if ($user->hasRole('treasurer')) {
            $treasurerCount = User::whereHas('organizations', fn($q) => $q->where('organization_id', $organizationId))
                ->whereHas('roles', fn($q) => $q->where('name', 'treasurer'))
                ->count();

            if ($treasurerCount <= 1) {
                return back()->withErrors(['error' => 'Cannot remove the last treasurer from the organization.']);
            }
        }

        try {
            // Remove user from organization (but don't delete the user account)
            $user->organizations()->detach($organizationId);

            return redirect()->route('treasurer.users.index')
                ->with('success', 'User removed from organization successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to remove user from organization.']);
        }
    }

    /**
     * Confirm adding existing user to organization.
     */
    public function confirmAddExisting(Request $request)
    {
        $organizationId = selected_org()->id;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return back()->withErrors(['error' => 'User not found.']);
        }

        // Check if user is already in this organization
        $userInOrg = UserOrganization::where('user_id', $user->id)
            ->where('organization_id', $organizationId)
            ->first();

        if ($userInOrg) {
            return back()->withErrors(['error' => 'User is already a member of this organization.']);
        }

        try {
            // Add user to organization
            $user->organizations()->attach($organizationId);

            // Assign role
            $user->addRole($request->role);

            return redirect()->route('treasurer.users.index')
                ->with('success', 'User added to organization successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to add user to organization.']);
        }
    }
}
