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
        Schema::table('approvers', function (Blueprint $table) {
            $table->dropForeign(['viewing_id']); // 外部キーのカラム名を適切に設定してください
        });
    }
    
    public function down()
    {
        Schema::table('approvers', function (Blueprint $table) {
            $table->foreign('viewing_id')->references('id')->on('viewings'); // 必要に応じて他の外部キーのオプションも設定してください
        });
    }
};
