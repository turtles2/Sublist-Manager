<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    public $table = "locations";

    protected $fillable = [
        'name','address','city','state','zip'
   ];
}
