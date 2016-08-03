<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePastPushTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pusher.tables.past_push'), function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('message');
            $table->integer('status_code');
            $table->integer('multicast_id');
            $table->integer('success');
            $table->integer('failure');
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
        Schema::drop(config('pusher.tables.past_push'));
    }
}
