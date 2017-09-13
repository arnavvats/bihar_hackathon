<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password','user_types_id','email_token','paid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function type(){
        return $this->belongsTo('App\UserType');

    }
    public function bills(){
        return $this->hasMany('App\Bill');
    }
    public function invoices(){
        return $this->hasMany('App\Invoice');
    }
    public function verified()
    {
        $this->verified = 1;
        $this->email_token = null;
        $this->save();
    }
    public static function verifiedUsers(){
        $users = User::all()->where('verified',1);
        return $users;
    }
    public static function unverifiedUsers(){
        $users = User::all()->where('verified',0);
        return $users;
    }

}
