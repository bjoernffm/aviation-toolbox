<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunwaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('runways', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('airport_ref');
            $table->string('airport_ident');
            $table->integer('length_ft')->nullable();
            $table->integer('width_ft')->nullable();
            $table->string('surface')->nullable();
            $table->integer('lighted')->nullable();
            $table->integer('closed')->nullable();
            $table->string('le_ident')->nullable();
            $table->double('le_latitude_deg')->nullable();
            $table->double('le_longitude_deg')->nullable();
            $table->integer('le_elevation_ft')->nullable();
            $table->integer('le_heading_degT')->nullable();
            $table->integer('le_displaced_threshold_ft')->nullable();
            $table->string('he_ident')->nullable();
            $table->double('he_latitude_deg')->nullable();
            $table->double('he_longitude_deg')->nullable();
            $table->integer('he_elevation_ft')->nullable();
            $table->integer('he_heading_degT')->nullable();
            $table->integer('he_displaced_threshold_ft')->nullable();
            $table->timestamps();

            $table->index('airport_ref');
            $table->index('airport_ident');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('runways');
    }
}
