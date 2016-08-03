<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuturePushUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('pusher.tables.future_push_user'), function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('future_push_id');
            $table->integer('user_id');
            $table->timestamps();

            $table->foreign('future_push_id')
                ->references('id')->on(config('pusher.tables.future_push'))
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('user_id')
                ->references('user_id')->on(config('pusher.tables.users'))
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(config('pusher.tables.future_push_user'));
    }
}
