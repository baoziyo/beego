<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateRole extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false)->unique('uniq_id');
            $table->string('name', 64)->nullable(false)->comment('权限名称');
            $table->string('status')->default('enabled')->nullable(false)->comment('状态:enabled 启用;disabled 禁用;');
            $table->text('data')->default(null)->nullable(false)->comment('权限配置');
            $table->timestamp('createdTime')->nullable()->comment('创建时间');
            $table->timestamp('updatedTime')->nullable()->comment('更新时间');
            $table->timestamp('deletedTime')->nullable()->comment('删除时间');
            $table->comment('角色表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role');
    }
}
