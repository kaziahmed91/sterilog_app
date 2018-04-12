<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSporeTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spore_test', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entry_operator_id');
            $table->integer('removal_operator_id')->nullable();
            $table->integer('company_id');
            $table->tinyInteger('control_sterile');
            $table->tinyInteger('test_sterile');
            $table->string('initial_comments')->nullable();
            $table->string('additional_comments')->nullable();
            $table->integer('entry_cycle_number');
            $table->integer('sterilizer_id');
            $table->integer('lot_number'); 
            $table->timestamp('entry_at');
            $table->timestamp('removal_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spore_test');

    }
}
