<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAirportFrequenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airport_frequencies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('airport_ref');
            $table->string('airport_ident');
            $table->string('type')->nullable();
            $table->string('description')->nullable();
            $table->float('frequency_mhz', 6, 3)->nullable();
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
        Schema::dropIfExists('airport_frequencies');
    }
}
