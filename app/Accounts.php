<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Crypt;

class Accounts extends Model
{
     public $table = "accounts";
    
     protected $fillable = [
        'username', 'password','employer','user_id'
    ];
    
           public function setusernameAttribute($value)
    {
        $this->attributes['username'] = Crypt::encrypt($value);
    }
    
      public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    
    public function setpasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encrypt($value);
    }
    
    public function getusernameAttribute($value)
    {
        return Crypt::decrypt($value);
    }
    
    public function getpasswordAttribute($value)
    {
        return Crypt::decrypt($value);
    }
}
