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
        Schema::create('view_approvers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('view_group_id');
            $table->unsignedBigInteger('user_id');
            
            $table->foreign('view_group_id')->references('id')->on('view_groups')->onDelete('cascade');
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
        Schema::dropIfExists('view_approvers');
    }
};
