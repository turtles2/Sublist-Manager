<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('sub_shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('starts');	
            $table->dateTime('ends');
            $table->date('posted');
            $table->date('taken')->nullable();
            $table->smallInteger('code');	
            $table->integer('poster')->unsigned();
            $table->foreign('poster')->references('id')->on('contacts')->onDelete('cascade');
            $table->integer('covered')->unsigned()->nullable();
            $table->foreign('covered')->references('id')->on('contacts')->onDelete('cascade');
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
        Schema::drop('contacts');
    }
}
