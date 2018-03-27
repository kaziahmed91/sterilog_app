<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSterilizerIdentifiers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sterilizers', function (Blueprint $table) {
            $table->string('manufacturer')->nullable();
            $table->string('serial')->nullabe();
            $table->tinyInteger('active')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sterilizers', function (Blueprint $table) {
            //
        });
    }
}
