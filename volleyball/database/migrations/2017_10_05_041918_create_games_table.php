<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date');
            $table->integer('team1')->unsigned();
            $table->integer('team2')->unsigned();
            $table->string('round');
            $table->enum('winner', ['none','team1', 'team2', 'tie'])->default('none');
            $table->string('location');
            $table->string('court');
            $table->enum('league', ['women', 'co-ed']);
            $table->timestamps();

            $table->foreign('team1')->references('id')->on('teams');
            $table->foreign('team2')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
