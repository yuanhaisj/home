<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyUserBonusTable extends Migration
{
    /**
     * Run the migrations.
     * 用户红包表
     * @return void
     */
    public function up()
    {
        Schema::create('jy_user_bonus', function (Blueprint $table) {
            $table->increments('id')->comment('用户红包表');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('bonus_id')->default(0)->comment('红包id');
            $table->timestamp('start_time')->comment('红包使用开始日期');
            $table->timestamp('end_time')->comment('红包使用截止时间');
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
        Schema::dropIfExists('jy_user_bonus');
    }
}
