<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{

    protected $fillable = ['user_id', 'name'];

    public function tenant()
    {
        return $this->hasOne('App\Tenant');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'memberships', 'organization_id', 'user_id');
    }


    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function memberships()
    {
        return $this->hasMany('App\Membership');
    }
}
