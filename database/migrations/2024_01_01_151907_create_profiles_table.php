<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('last_name_kana');
            $table->string('first_name_kana');
            $table->integer('year_of_birth');
            $table->integer('month_of_birth');
            $table->integer('date_of_birth');
            $table->string('age');
            $table->string('sex');
            $table->string('zip');
            $table->string('prefecture');
            $table->string('municipalities');
            $table->string('other_address')->nullable();
            $table->string('tel');
            $table->string('school_type');
            $table->string('school_name');
            $table->integer('enrollment_year');
            $table->integer('graduation_year');
            $table->string('graduation_type');
            $table->string('faculty_and_department');
            $table->string('literature_or_science');
            $table->string('job_change_experience');
            $table->string('employment_status');
            $table->string('latest_annual_income');
            $table->string('id_photo')->nullable();
            $table->timestamps();
            // ユーザー削除するとプロフィールも削除される
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
        Schema::dropIfExists('profiles');
    }
}
