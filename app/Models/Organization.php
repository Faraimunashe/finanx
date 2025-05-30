<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Organization extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = ['name', 'address', 'phone', 'email'];


    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_organizations');
    }

    public function selected_by_users()
    {
        return $this->hasMany(SelectedOrganization::class);
    }

}
