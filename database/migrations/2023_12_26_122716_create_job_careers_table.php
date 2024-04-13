<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCareersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_careers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('job_careers_company_name');
            $table->integer('year_of_joining');
            $table->integer('month_of_joining');
            $table->integer('year_of_retirement');
            $table->integer('month_of_retirement');
            $table->string('employment_status');
            $table->string('job_careers_industry');
            $table->string('number_of_employees')->nullable();
            $table->string('department_or_post')->nullable();
            $table->string('job_details', 2000)->nullable();
            $table->timestamps();
            // ユーザー削除すると職務経歴も削除される
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
        Schema::dropIfExists('job_careers');
    }
}
