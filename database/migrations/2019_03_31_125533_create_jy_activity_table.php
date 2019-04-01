<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyActivityTable extends Migration
{
    /**
     * Run the migrations.
     * 运营管理
     * @return void
     */
    public function up()
    {
        Schema::create('jy_activity', function (Blueprint $table) {
            $table->increments('id')->comment('运营管理');
            $table->string('name',20)->default('')->comment('活动名称');
            $table->timestamp('start_time')->comment('开始时间');
            $table->timestamp('end_time')->comment('结束时间');
            $table->string('activity_config',50)->default('')->comment('活动配置');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jy_activity');
    }
}
