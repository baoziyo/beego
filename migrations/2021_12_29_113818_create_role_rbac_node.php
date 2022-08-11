<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class CreateRoleRbacNode extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_rbac_node', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false)->unique('uniq_id');
            $table->string('name', 64)->nullable(false)->comment('菜单名称');
            $table->string('status')->default('enabled')->nullable(false)->comment('状态:enabled 启用;disabled 禁用;');
            $table->string('link')->default('')->nullable(false)->comment('路由;');
            $table->string('type')->default('')->nullable(false)->comment('类型:module 模块;controller 控制器;node 节点;option 操作;');
            $table->unsignedBigInteger('parentId')->default(0)->nullable(false)->comment('父id');
            $table->string('module')->default('')->nullable(false)->comment('模块');
            $table->string('controller')->default('')->nullable(false)->comment('控制器');
            $table->string('node')->default('')->nullable(false)->comment('节点');
            $table->string('option')->default('')->nullable(false)->comment('操作');
            $table->string('style')->default('')->nullable(false)->comment('样式');
            $table->string('icon')->default('')->nullable(false)->comment('图标');
            $table->unsignedInteger('sort')->default(0)->nullable(false)->comment('排序');
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
        Schema::dropIfExists('role_rbac_node');
    }
}
