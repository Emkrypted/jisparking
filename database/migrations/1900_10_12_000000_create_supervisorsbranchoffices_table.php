<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupervisorsBranchOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisors_branch_offices', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_office_id');
            $table->foreign('branch_office_id')->references('branch_office_id')->on('branch_offices');
            $table->integer('rut', 150)->unsigned();
            $table->foreign('rut')->references('rut')->on('users');
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
        Schema::dropIfExists('supervisors_branch_offices');
    }
}
