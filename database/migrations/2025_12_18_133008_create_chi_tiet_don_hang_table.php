<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_don_hang', function (Blueprint $table) {
            $table->id('ma_chi_tiet');
            $table->unsignedBigInteger('ma_don_hang');
            $table->unsignedBigInteger('ma_sach');
            $table->integer('so_luong');
            $table->decimal('gia_ban', 10, 2);
            $table->decimal('gia_khuyen_mai', 10, 2)->nullable();
            $table->decimal('thanh_tien', 12, 2);
            $table->timestamps();
            
            $table->foreign('ma_don_hang')->references('ma_don_hang')->on('don_hang')->onDelete('cascade');
            $table->foreign('ma_sach')->references('ma_sach')->on('sach')->onDelete('cascade');
            
            $table->index(['ma_don_hang']);
            $table->index(['ma_sach']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_don_hang');
    }
};