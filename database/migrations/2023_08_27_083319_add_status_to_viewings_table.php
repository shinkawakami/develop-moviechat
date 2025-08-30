<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $withinTransaction = false;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('viewings', function (Blueprint $table) {
            $table->enum('status', ['視聴前', '視聴中', '視聴終了'])->default('視聴前');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('viewings', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
