<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_Codes extends Model
{
    public $table = "job_codes";

    protected $fillable = [
        'name','code','location',
   ];

   public function location()
   {
         return $this->belongsTo('App\Locations', 'location', 'id');
   }
}
