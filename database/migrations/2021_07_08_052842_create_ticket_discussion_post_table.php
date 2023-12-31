<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketDiscussionPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_discussion_post', function (Blueprint $table) {

            $table->increments('id');
            $table->text('content');
            $table->unsignedInteger("user_id");
            $table->unsignedInteger("tickets_discussion_id");
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
        Schema::dropIfExists('ticket_discussion_post');
    }
}