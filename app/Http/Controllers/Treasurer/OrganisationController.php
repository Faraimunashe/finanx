<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\SelectedOrganization;
use App\Models\UserOrganization;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $organizations = Auth::user()->organizations;
            return inertia('Treasurer/OrganizationsPage', [
                'organizations' => $organizations
            ]);
        } catch (Exception $e) {
            //Log::error('Error fetching organizations: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'address' => 'nullable|string',
                'phone'   => 'nullable|string|max:20',
                'email'   => 'nullable|email|max:255',
            ]);

            $organization = Organization::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email
            ]);

            UserOrganization::create([
                'user_id' => Auth::id(),
                'organization_id' => $organization->id
            ]);

            return back()->with('success', 'Organization created successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $organization = Auth::user()->organizations()->findOrFail($id);
            select_org($id);
            return inertia('Treasurer/Organization/ShowOrganizationPage', [
                'organization' => $organization
            ]);
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $organization = Organization::findOrFail($id);
            return inertia('Treasurer/Organization/EditOrganizationPage', [
                'organization' => $organization
            ]);
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name'    => 'required|string|max:255',
                'address' => 'nullable|string',
                'phone'   => 'nullable|string|max:20',
                'email'   => 'nullable|email|max:255',
            ]);

            Organization::findOrFail($id)->update([
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email
            ]);

            return back()->with('success', 'Organization updated successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $organization = Organization::findOrFail($id);
            $organization->delete();

            return back()->with('success', 'Organization deleted successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
