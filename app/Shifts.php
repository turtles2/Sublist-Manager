<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
     public $table = "shifts";
    
     protected $fillable = [
         'starts','ends','worker','code','sync'
    ];
    
        public function worker()
    {
        return $this->belongsTo('App\Contacts','worker','id');
    }
}
