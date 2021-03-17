<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('inventory_id');
            $table->integer('supplier_id')->unsigned();
            $table->foreign('supplier_id')->references('rut')->on('users');
            $table->unsignedBigInteger('branch_office_id');
            $table->foreign('branch_office_id')->references('branch_office_id')->on('branch_offices');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->unsignedBigInteger('inventory_type_id');
            $table->foreign('inventory_type_id')->references('inventory_type_id')->on('inventory_types');
            $table->integer('document_number');
            $table->integer('cost');
            $table->integer('qty');
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
        Schema::dropIfExists('inventories');
    }
}
