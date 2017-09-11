<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $fillable = ['name'];
    public function users(){
        return $this->hasMany('App\User','user_types_id');
    }
}
