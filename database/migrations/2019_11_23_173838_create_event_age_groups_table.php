<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventAgeGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_age_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_event_age_group');
            $table->unsignedBigInteger('fk_age_group');

            $table->foreign('fk_event_age_group', 'fk_event_age_group')
                ->references('id')->on('events')
                ->onDelete('no action');
            $table->foreign('fk_age_group', 'fk_age_group')
                ->references('id')->on('age_groups')
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
        Schema::dropIfExists('event_age_groups');
    }
}
