<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('post', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');

            $table->unsignedInteger("user_id");
            $table->unsignedInteger("client_id");

            $table->unsignedTinyInteger("approved")->nullable()->default(0);
            $table->unsignedInteger("approved_by")->nullable()->default(0);


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
        Schema::dropIfExists('post');
    }
}
