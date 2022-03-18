<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fixtures', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('home_team_id')->unsigned();
            $table->bigInteger('away_team_id')->unsigned();
            $table->bigInteger('stadium_id')->unsigned();
            $table->smallInteger('week')->unsigned();
            $table->tinyInteger('home_team_score')->unsigned()->nullable();
            $table->tinyInteger('away_team_score')->unsigned()->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('home_team_id')->on('teams')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('away_team_id')->on('teams')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('stadium_id')->on('stadiums')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fixtures');
    }
};
