<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('starts');	
            $table->dateTime('ends');
            $table->smallInteger('code');	
            $table->integer('worker')->unsigned();
            $table->foreign('worker')->references('id')->on('contacts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('shifts');
    }
}
