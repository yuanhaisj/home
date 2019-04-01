<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJyCommentTable extends Migration
{
    /**
     * Run the migrations.
     * 评论表
     * @return void
     */
    public function up()
    {
        Schema::create('jy_comment', function (Blueprint $table) {
            $table->increments('id')->comment('评论表');
            $table->integer('user_id')->default(0)->comment('评论');
            $table->enum('type',[1,2])->default(1)->comment('1商品  2 文章');
            $table->integer('comment_id')->default(0)->comment('评论的商品或文章id');
            $table->string('cotent',255)->default('')->comment('评论内容');
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
        Schema::dropIfExists('jy_comment');
    }
}
