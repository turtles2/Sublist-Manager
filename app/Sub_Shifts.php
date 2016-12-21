<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_Shifts extends Model
{
     public $table = "sub_shifts";
    
     protected $fillable = [
         'starts','ends','posted','taken','code','poster','covered','sync'
    ];
    
        public function poster()
    {
        return $this->belongsTo('App\Contacts','poster','id');
    }
    
        public function covered()
    {
        return $this->belongsTo('App\Contacts','covered','id');
    }
}
