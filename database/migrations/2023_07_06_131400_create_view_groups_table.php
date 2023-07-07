<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('view_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('requester_id')->nullable();
            $table->unsignedBigInteger('movie_id')->nullable();
            $table->string('view_link')->nullable();
            $table->timestamp('start_time');
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('view_groups');
    }
};
