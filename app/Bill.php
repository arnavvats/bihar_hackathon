<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = ['description','scanned_copy_path','verified'];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function invoice(){
        return $this->hasOne('App\Invoice');
    }
}
