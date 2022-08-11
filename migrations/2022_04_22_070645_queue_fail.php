<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class QueueFail extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queue_fail', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false)->unique('uniq_id');
            $table->unsignedBigInteger('targetId')->nullable(false)->comment('通知id')->unique('uniq_targetId');
            $table->text('failUserIds')->nullable(false)->comment('发送失败用户Ids');
            $table->unsignedTinyInteger('sendCount')->nullable(false)->default(1)->comment('发送次数');
            $table->text('failDetails')->nullable(false)->comment('失败详情');
            $table->timestamp('createdTime')->nullable()->comment('创建时间');
            $table->timestamp('updatedTime')->nullable()->comment('更新时间');
            $table->comment('队列失败表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_fail');
    }
}
