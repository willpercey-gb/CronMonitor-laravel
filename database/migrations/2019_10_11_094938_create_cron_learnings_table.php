<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronLearningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cron_learnings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("cron_id");
            $table->dateTime("start_time");
            $table->dateTime("end_time")->nullable();
            $table->boolean("did_fail")->default(0);
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
        Schema::dropIfExists('cron_learnings');
    }
}
