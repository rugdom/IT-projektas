<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->date('date');
            $table->time('time');
            $table->string('image');
            $table->string('region');
            $table->string('address');
            $table->boolean('free');
            $table->string('link_to_buy')->nullable();
            $table->unsignedBigInteger('fk_link')->nullable();

            $table->index(["fk_link"], 'fk_link');


            $table->foreign('fk_link', 'fk_link')
                ->references('id')->on('events')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
