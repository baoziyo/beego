<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

class Token extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('token', static function (Blueprint $table) {
            $table->unsignedBigInteger('id')->nullable(false)->unique('uniq_id');
            $table->string('key', 64)->nullable(false)->comment('token 名称')->unique('uniq_key');
            $table->string('value', 512)->nullable(false)->comment('token 值');
            $table->unsignedInteger('expires')->nullable(false)->default(0)->comment('有效期(秒)');
            $table->timestamp('expiresTime')->nullable()->comment('过期时间');
            $table->timestamp('createdTime')->nullable()->comment('创建时间');
            $table->comment('token表');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token');
    }
}
