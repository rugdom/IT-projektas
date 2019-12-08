<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('participation');
            $table->boolean('only_free');
            $table->integer('period');
            $table->integer('inform_before');
            $table->string('region')->nullable();
            $table->unsignedBigInteger('fk_order_event')->nullable();
            $table->unsignedBigInteger('fk_order_user');
            $table->timestamps();

            $table->foreign('fk_order_event', 'fk_order_event')
                ->references('id')->on('events')
                ->onDelete('no action');
            $table->foreign('fk_order_user', 'fk_order_user')
                ->references('id')->on('users')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
