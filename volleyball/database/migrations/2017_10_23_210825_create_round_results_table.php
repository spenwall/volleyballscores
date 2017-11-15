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
            $table->enum('league', ['ladies', 'co-ed']);
            $table->integer('tier');
            $table->integer('wins')->default(0);
            $table->integer('loses')->default(0);
            $table->integer('ties')->default(0);
            $table->integer('final_standing');
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
