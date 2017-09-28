<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('emails_notification', function(Blueprint $table) {
            $table->increments('id');
            $table->string('module')->nullable();
            $table->integer('sender_name')->nullable();
            $table->string('to')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->string('subject')->nullable();
            $table->longText('message')->nullable();
            $table->date('sent_date')->nullable();
            $table->integer('status')->default(0)->comment = "0:Pending, 1: Sent, 2: In Progress";
            $table->softDeletes();
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
        //
        Schema::dropIfExists('emails_notification');
    }
}
