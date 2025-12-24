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
        Schema::create('sach', function (Blueprint $table) {
            $table->id('ma_sach');
            $table->string('ten_sach');
            $table->string('duong_dan')->unique();
            $table->text('mo_ta')->nullable();
            $table->text('noi_dung')->nullable();
            $table->decimal('gia_ban', 10, 2);
            $table->decimal('gia_khuyen_mai', 10, 2)->nullable();
            $table->integer('so_luong_ton')->default(0);
            $table->integer('so_trang')->nullable();
            $table->string('kich_thuoc')->nullable();
            $table->decimal('trong_luong', 8, 2)->nullable();
            $table->string('hinh_anh')->nullable();
            $table->json('hinh_anh_phu')->nullable();
            $table->boolean('trang_thai')->default(1);
            $table->boolean('noi_bat')->default(0);
            $table->date('ngay_xuat_ban')->nullable();
            $table->string('isbn')->nullable()->unique();
            $table->integer('luot_xem')->default(0);
            $table->decimal('diem_trung_binh', 3, 2)->default(0);
            $table->integer('so_luot_danh_gia')->default(0);
            $table->unsignedBigInteger('ma_the_loai')->nullable();
            $table->unsignedBigInteger('ma_tac_gia')->nullable();
            $table->unsignedBigInteger('ma_nxb')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            
            $table->foreign('ma_the_loai')->references('ma_the_loai')->on('the_loai')->onDelete('set null');
            $table->foreign('ma_tac_gia')->references('ma_tac_gia')->on('tac_gia')->onDelete('set null');
            $table->foreign('ma_nxb')->references('ma_nxb')->on('nha_xuat_ban')->onDelete('set null');
            
            $table->index(['trang_thai', 'noi_bat']);
            $table->index(['ma_the_loai', 'trang_thai']);
            $table->index(['ma_tac_gia', 'trang_thai']);
            $table->index(['ma_nxb', 'trang_thai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sach');
    }
};