<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateUser extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false)->unique('uniq_id');
            $table->string('name', 64)->nullable(false)->comment('用户名');
            $table->string('password', 128)->nullable(false)->comment('密码');
            $table->string('salt', 16)->nullable(false)->comment('密码盐');
            $table->string('phone', 13)->default('')->nullable(false)->comment('联系电话')->unique('uniq_phone');
            $table->string('email')->default('')->nullable(false)->comment('邮箱');
            $table->unsignedBigInteger('role')->default(0)->nullable(false)->comment('角色id');
            $table->string('status')->default('enabled')->nullable(false)->comment('状态:enabled 启用;disabled 禁用;');
            $table->string('isAdmin')->default('disabled')->nullable(false)->comment('管理员:enabled 启用;disabled 禁用;');
            $table->string('avatar', 256)->default('')->nullable(false)->comment('头像');
            $table->timestamp('lasLoginTime')->nullable()->comment('最后登陆时间');
            $table->timestamp('createdTime')->nullable()->comment('创建时间');
            $table->timestamp('updatedTime')->nullable()->comment('更新时间');
            $table->timestamp('deletedTime')->nullable()->comment('删除时间');
            $table->comment('用户表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
}
