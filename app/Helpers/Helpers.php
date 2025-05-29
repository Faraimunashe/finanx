<?php

use App\Models\SelectedOrganization;
use Illuminate\Support\Facades\Auth;

function select_org($id)
{
    SelectedOrganization::updateOrCreate(
        ['user_id' => auth()->id()],
        ['organization_id' => $id]
    );
}

function selected_org()
{
    return SelectedOrganization::with('organization')
                    ->where('user_id', Auth::id())
                    ->first();
}
