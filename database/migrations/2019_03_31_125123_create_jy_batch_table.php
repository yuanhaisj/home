<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyBatchTable extends Migration
{
    /**
     * Run the migrations.
     * 批次数据表
     * @return void
     */
    public function up()
    {
        Schema::create('jy_batch', function (Blueprint $table) {
            $table->increments('id')->comment('批次数据表');
            $table->string('file_path',50)->default('')->comment('批次文件路径');
            $table->enum('type',[1,2,3])->default(1)->comment('1发红包  2、发短信  3、邮件');
            $table->string('content',50)->default('')->comment('批次的内容');
            $table->enum('status',[1,2,3])->default(1)->comment('1未审核 2、待发送  3、已发送');
            $table->string('note',20)->default('')->comment('备注信息');
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
        Schema::dropIfExists('jy_batch');
    }
}
