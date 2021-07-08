<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketDiscussionPostDocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_discussion_post_doc', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('tickets_discussion_id')->unsigned();
            $table->integer('post_id')->unsigned();
            $table->string('file')->nullable();
            $table->string('name')->nullable();
            $table->string('size')->nullable();
            $table->integer('uploaded_by')->unsigned();
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
        Schema::drop('tickets_discussion_post_doc');
    }
}