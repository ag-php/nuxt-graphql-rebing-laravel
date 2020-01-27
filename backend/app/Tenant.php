<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = ['database'];

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }
}
