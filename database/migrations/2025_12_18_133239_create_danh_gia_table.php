<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danh_gia', function (Blueprint $table) {
            $table->id('ma_danh_gia');
            $table->unsignedBigInteger('ma_nguoi_dung');
            $table->unsignedBigInteger('ma_sach');
            $table->integer('diem_danh_gia')->unsigned()->comment('1-5 stars');
            $table->text('noi_dung_danh_gia')->nullable();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('cho_duyet');
            $table->text('ly_do_tu_choi')->nullable();
            $table->timestamps();
            
            $table->foreign('ma_nguoi_dung')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ma_sach')->references('ma_sach')->on('sach')->onDelete('cascade');
            
            $table->unique(['ma_nguoi_dung', 'ma_sach']);
            $table->index(['ma_sach', 'trang_thai']);
            $table->index(['diem_danh_gia']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danh_gia');
    }
};