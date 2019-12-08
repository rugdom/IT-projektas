<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAgeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_age_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_order_age_group');
            $table->unsignedBigInteger('fk_ageGroup');

            $table->foreign('fk_order_age_group', 'fk_order_age_group')
                ->references('id')->on('orders')
                ->onDelete('cascade');
            $table->foreign('fk_ageGroup', 'fk_ageGroup')
                ->references('id')->on('age_groups')
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
        Schema::dropIfExists('order_age_groups');
    }
}
