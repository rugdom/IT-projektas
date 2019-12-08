<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_event_type');
            $table->unsignedBigInteger('fk_type');

            $table->foreign('fk_event_type', 'fk_event_type')
                ->references('id')->on('events')
                ->onDelete('no action');
            $table->foreign('fk_type', 'fk_type')
                ->references('id')->on('types')
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
        Schema::dropIfExists('event_types');
    }
}
