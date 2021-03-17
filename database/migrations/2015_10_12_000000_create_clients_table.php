<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->integer('rut', 150)->unsigned();
            $table->foreign('rut')->references('rut')->on('users');
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')->references('region_id')->on('regions');
            $table->unsignedBigInteger('commune_id');
            $table->foreign('commune_id')->references('commune_id')->on('communes');
            $table->string('activity');
            $table->longText('address');
            $table->integer('status');
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
        Schema::dropIfExists('clients');
    }
}
