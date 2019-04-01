<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyUserTable extends Migration
{
    /**
     * Run the migrations.
     * 会员表
     * @return void
     */
    public function up()
    {
        Schema::create('jy_user', function (Blueprint $table) {
            $table->increments('id');
            $table->char('phone',11)->comment("手机号");
            $table->string('username',30)->default('')->comment('用户名');
            $table->string('password',32)->default('')->comment('用户密码');
            $table->string('image_url',150)->default('')->comment('用户头像');
            $table->integer('score')->default(0)->comment('用户积分');
            $table->decimal('balance',10,2)->default('0.00')->comment('用户余额');
            $table->enum('status',[1,2,3])->default(1)->comment('1 未激活 2 正常 3 注销，禁用');
            $table->integer('address_id')->default(0)->comment('默认地址id');
            $table->timestamps();
            $table->index('username');
            $table->unique('phone');
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jy_user');
    }
}
