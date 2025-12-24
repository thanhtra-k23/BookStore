<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gio_hang', function (Blueprint $table) {
            $table->id('ma_gio_hang');
            $table->unsignedBigInteger('ma_nguoi_dung');
            $table->unsignedBigInteger('ma_sach');
            $table->integer('so_luong');
            $table->decimal('gia_tai_thoi_diem', 10, 2);
            $table->timestamps();
            
            $table->foreign('ma_nguoi_dung')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ma_sach')->references('ma_sach')->on('sach')->onDelete('cascade');
            
            $table->unique(['ma_nguoi_dung', 'ma_sach']);
            $table->index(['ma_nguoi_dung']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gio_hang');
    }
};