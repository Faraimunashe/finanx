<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedOrganization extends Model
{
    protected $fillable = ['user_id', 'organization_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'organization_currencies', 'organization_id', 'currency_id')
                    ->withTimestamps();
    }
}
