<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\helpers;

class Contacts extends Model
{
     public $table = "contacts";
    
     protected $fillable = [
        'fname', 'lname','phone','email','join_date','status','account_id','manager'
    ];
    
         public function account()
    {
        return $this->belongsTo('App\Accounts','account_id','id');
    }
    
     public function setphoneAttribute($value)
    {
        $this->attributes['phone'] = helpers::normalize_phone($value);
    }
    
    public function listed_shifts()
    {
        return $this->hasMany('App\Sub_Shifts','poster','id');
    }
    
    public function taken_shifts()
    {
        return $this->hasMany('App\Sub_Shifts','covered','id');
    }
    
    public function shifts()
    {
        return $this->hasMany('App\Shifts','worker','id');
    }
}
