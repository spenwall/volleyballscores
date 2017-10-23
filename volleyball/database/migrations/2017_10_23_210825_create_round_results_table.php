<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoundResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('round_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('round_id');
            $table->integer('team_id');
            $table->integer('rank');
            $table->integer('tier');
            $table->integer('wins');
            $table->integer('loses');
            $table->integer('ties');
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
        Schema::dropIfExists('round_results');
    }
}