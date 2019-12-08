<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_keywords', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_event_keyword');
            $table->unsignedBigInteger('fk_keyword');

            $table->foreign('fk_event_keyword', 'fk_event_keyword')
                ->references('id')->on('events')
                ->onDelete('no action');
            $table->foreign('fk_keyword', 'fk_keyword')
                ->references('id')->on('keywords')
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
        Schema::dropIfExists('event_keywords');
    }
}
