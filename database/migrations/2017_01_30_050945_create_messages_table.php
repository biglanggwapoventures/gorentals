<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sent_to');
            $table->unsignedInteger('sent_from');
            $table->text('message');
            $table->timestamp('seen_at')->nullable();
            $table->timestamps();

            $table->foreign('sent_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sent_from')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
