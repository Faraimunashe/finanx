<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Currency extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['code', 'country'];

    public function organizations()
    {
        return $this->belongsToMany(Organization::class, 'organization_currencies', 'currency_id', 'organization_id')
                    ->withTimestamps();
    }

}
