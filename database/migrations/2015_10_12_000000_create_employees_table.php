<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('rut', 150)->unsigned();
            $table->foreign('rut')->references('rut')->on('users');
            $table->unsignedBigInteger('gender_id');
            $table->foreign('gender_id')->references('gender_id')->on('genders');
            $table->unsignedBigInteger('nationality_id');
            $table->foreign('nationality_id')->references('nationality_id')->on('nationalities');
            $table->unsignedBigInteger('civil_state_id');
            $table->foreign('civil_state_id')->references('civil_state_id')->on('civil_states');
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')->references('region_id')->on('regions');
            $table->unsignedBigInteger('providence_id');
            $table->foreign('providence_id')->references('providence_id')->on('providences');
            $table->unsignedBigInteger('commune_id');
            $table->foreign('commune_id')->references('commune_id')->on('communes');
            $table->unsignedBigInteger('health_id');
            $table->foreign('health_id')->references('health_id')->on('healths');
            $table->unsignedBigInteger('pention_id');
            $table->foreign('pention_id')->references('pention_id')->on('pentions');
            $table->unsignedBigInteger('contract_type_id');
            $table->foreign('contract_type_id')->references('contract_type_id')->on('contract_types');
            $table->unsignedBigInteger('branch_office_id');
            $table->foreign('branch_office_id')->references('branch_office_id')->on('branch_offices');
            $table->longText('father_lastname');
            $table->longText('mother_lastname');
            $table->longText('address');
            $table->string('phone');
            $table->string('cellphone');
            $table->date('born_date');
            $table->date('entrance_health');
            $table->date('entrance_pention');
            $table->date('entrance_company');
            $table->date('exit_company');
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
        Schema::dropIfExists('employees');
    }
}
