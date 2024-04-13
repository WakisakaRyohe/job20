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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bord_id');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->boolean('user_flg');
            $table->string('message', 1000);
            $table->timestamps();
            // 掲示板削除すると、メッセージも削除される
            $table->foreign('bord_id')->references('id')->on('bords')->onDelete('cascade');
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
