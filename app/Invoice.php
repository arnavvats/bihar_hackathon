<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['description','scanned_copy_path','bill_id'];
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function bill(){
        return $this->belongsTo('App\Bill');
    }
}
