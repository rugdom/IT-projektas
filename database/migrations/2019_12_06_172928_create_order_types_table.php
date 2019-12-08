<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_types', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_order_type');
            $table->unsignedBigInteger('fk_type_id');

            $table->foreign('fk_order_type', 'fk_order_type')
                ->references('id')->on('orders')
                ->onDelete('cascade');
            $table->foreign('fk_type_id', 'fk_type_id')
                ->references('id')->on('types')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_types');
    }
}
