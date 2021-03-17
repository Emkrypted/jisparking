<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('rut', 150)->unsigned();
            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('rol_id')->on('rols');
            $table->unsignedBigInteger('providence_id');
            $table->foreign('providence_id')->references('providence_id')->on('providences');
            $table->unsignedBigInteger('commune_id');
            $table->foreign('commune_id')->references('commune_id')->on('communes');
            $table->string('names');
            $table->string('email');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
