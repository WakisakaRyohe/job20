<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('job_name', 100);
            $table->string('photo1');
            $table->string('photo2');
            $table->string('photo3');
            $table->string('job_catch');
            $table->string('job_summary', 1000);
            $table->string('job_description', 3000);
            $table->string('application_conditions', 1000);
            $table->string('work_location', 1000);
            $table->string('working_hours', 1000);
            $table->integer('annual_income');
            $table->string('salary', 1000);
            $table->string('holiday', 1000);
            $table->string('welfare', 1000);
            $table->string('selection', 1000);
            $table->unsignedBigInteger('company_id');
            $table->integer('industry_id');
            $table->integer('occupation_id');
            $table->integer('employment_status_id');
            $table->integer('prefecture_id');
            $table->string('commitments', 500);
            $table->integer('pickupFlg');
            $table->dateTime('deadline');
            $table->timestamps();
            // 会社削除すると求人も削除される
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
