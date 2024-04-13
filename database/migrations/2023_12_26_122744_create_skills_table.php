<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('certifications', 1000)->nullable();
            $table->string('speaking_english_level')->nullable();
            $table->string('reading_english_level')->nullable();
            $table->string('writing_english_level')->nullable();
            $table->integer('toeic_score')->nullable();
            $table->integer('toefl_score')->nullable();
            $table->string('others_language')->nullable();
            $table->string('input_others_language')->nullable();
            $table->string('reading_others_language_level')->nullable();
            $table->string('speaking_others_language_level')->nullable();
            $table->string('writing_others_language_level')->nullable();
            $table->timestamps();
            // ユーザー削除すると削除される
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skills');
    }
}
