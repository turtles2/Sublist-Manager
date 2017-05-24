<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_codes', function (Blueprint $table) {
           $table->increments('id');
           $table->smallInteger('code');
           $table->string('name');
           $table->integer('location')->unsigned();
           $table->foreign('location')->references('id')->on('locations')->onDelete('cascade');
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
        Schema::drop('job_codes');
    }
}
