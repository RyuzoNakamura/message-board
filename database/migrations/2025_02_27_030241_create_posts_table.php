<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id') //スレのID
                ->constrained()
                ->cascadeOnDelete();
            $table->string('poster_name')->default('名無しさん');  // 投稿者の表示名
            $table->string('ip_address'); // 投稿者のIPアドレス
            $table->text('body'); //本文
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
