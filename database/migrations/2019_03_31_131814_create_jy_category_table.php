<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyCategoryTable extends Migration
{
    /**
     * Run the migrations.
     * 商品分类表
     * @return void
     */
    public function up()
    {
        Schema::create('jy_category', function (Blueprint $table) {
            $table->increments('id')->comment('商品分类表');
            $table->integer('f_id')->default(0)->comment('上级分类');
            $table->string('cate_name',20)->default('')->comment('分类名称');
            $table->enum('status',[1,2])->default(1)->comment('状态 1可用 2禁用');
            $table->index('cate_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jy_category');
    }
}
