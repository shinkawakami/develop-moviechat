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
            $table->foreignId('group_id')->nullable()->constrained();
            $table->foreignId('requester_id')->nullable()->constrained('users');
            $table->foreignId('movie_id')->nullable()->constrained();
            $table->string('view_link')->nullable();
            $table->timestamp('start_time');
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
        Schema::dropIfExists('view_groups');
    }
};
