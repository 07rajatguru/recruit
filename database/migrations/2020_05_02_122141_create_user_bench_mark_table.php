<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBenchMarkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('user_bench_mark', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('no_of_resumes')->nullable();
            $table->string('shortlist_ratio')->nullable();
            $table->string('interview_ratio')->nullable();
            $table->string('selection_ratio')->nullable();
            $table->string('offer_acceptance_ratio')->nullable();
            $table->string('joining_ratio')->nullable();
            $table->string('after_joining_success_ratio')->nullable();
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
        Schema::dropIfExists('user_bench_mark');
    }
}
