<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('goods_comment', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('商品评论ID');
            $table->unsignedInteger('goods_id')->comment('商品ID,goods表的主键');
            $table->unsignedInteger('order_id')->index()->comment('订单编号|订单表GUID');
            $table->unsignedInteger('cargo_id')->index()->comment('商品ID,cargo表的主键');
            $table->unsignedInteger('user_id')->index()->comment('用户ID,user表的主键');
            $table->tinyInteger('comment_type')->default(0)->comment('匿名评价  0:匿名评价 1:显示用户名| 默认为0');
            $table->tinyInteger('star')->comment('1 好评 2 中评 3 差评');
            $table->text('comment_info')->comment('评价内容');
            $table->timestamps();
            $table->softDeletes()->comment('删除时间');

            $table->foreign('goods_id')->references('id')->on('goods')->comment('商品表id外键');
            $table->foreign('orders_details')->references('id')->on('orders')->comment('订单表id键');
            $table->foreign('cargo_id')->references('id')->on('goods_cargo')->comment('货品表id外键');
            $table->foreign('user_id')->references('id')->on('users_register')->comment('用户表id外键');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('goods_comment');
    }
}