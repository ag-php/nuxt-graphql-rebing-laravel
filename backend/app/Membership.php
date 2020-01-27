<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['user_id', 'organization_id'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization');
    }

    /**
     * Checks if a user is a member of an organization
     *
     * @param integer $user_id
     * @param integer $organization_id
     * @return boolean
     */
    public static function isMember(int $user_id, int $organization_id): bool {
        return !!self::where('user_id', $user_id)
            ->where('orgzanition_id', $organization_id)
            ->first();
    }
}
