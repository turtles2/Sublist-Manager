<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSyncColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_shifts', function ($table) {
            $table->boolean('sync')->default(false);
        });
        
         Schema::table('shifts', function ($table) {
            $table->boolean('sync')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_shifts', function ($table) {
            $table->dropColumn('sync');
        });
        
        Schema::table('shifts', function ($table) {
            $table->dropColumn('sync');
        });
    }
}
