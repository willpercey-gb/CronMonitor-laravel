<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCronsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('crons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("label");
            $table->bigInteger("owner_id");
            $table->dateTime("start_time")->nullable();
            $table->dateTime("end_time")->nullable();
//            $table->dateTime("start_time_1")->nullable();
//            $table->dateTime("end_time_1")->nullable();
            //$table->boolean("last")->default(1);
            $table->bigInteger("allowance")->default(3);
            $table->dateTime("next_expected_start")->nullable();
            $table->string("cron_pattern")->nullable();
            //$table->string("")
            $table->bigInteger("expected_duration")->default(15);
            $table->boolean("learning")->default(1);
            $table->boolean("did_fail")->default(0);
            $table->string("error_message")->nullable();
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
        Schema::dropIfExists('crons');
    }
}
