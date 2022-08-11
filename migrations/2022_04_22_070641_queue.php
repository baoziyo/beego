<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class Queue extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queue', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false)->unique('uniq_id');
            $table->string('queue', 32)->nullable(false)->default('')->comment('队列名称');
            $table->string('type', 256)->nullable(false)->default('')->comment('发送通道');
            $table->string('template', 64)->nullable(false)->default('')->comment('模版');
            $table->text('params')->nullable(false)->comment('模版参数');
            $table->text('sendUserIds')->nullable(false)->comment('接收用户Ids');
            $table->string('status')->nullable(false)->default('doing')->comment('发送状态: doing 等待发送; finished 发送成功; failed 发送失败;');
            $table->unsignedInteger('delay')->nullable(false)->default(0)->comment('延迟发送(sec)');
            $table->timestamp('createdTime')->nullable()->comment('创建时间');
            $table->timestamp('updatedTime')->nullable()->comment('更新时间');
            $table->comment('队列表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue');
    }
}
