<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationCurrency extends Model
{
    protected $fillable = [
        'organization_id',
        'currency_id',
    ];


    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }


    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
