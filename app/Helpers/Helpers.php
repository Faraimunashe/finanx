<?php

use App\Models\SelectedOrganization;

function select_org($id)
{
    SelectedOrganization::updateOrCreate(
        ['user_id' => auth()->id()],
        ['organization_id' => $id]
    );
}
