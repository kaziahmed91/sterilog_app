<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSterilizersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sterilizers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->string('sterilizer_name');
            $table->integer('added_by');
            $table->timestamps();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('date_deleted')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sterilizers');
    }
}
