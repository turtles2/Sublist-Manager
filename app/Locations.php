<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    public $table = "locations";

    protected $fillable = [
        'name','address','city','state','zip'
   ];

   public function jobs()
   {

       return $this->hasMany('App\Job_Codes', 'location', 'id');

   }
}
