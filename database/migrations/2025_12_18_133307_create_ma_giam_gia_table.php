<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ma_giam_gia', function (Blueprint $table) {
            $table->id('ma_giam_gia');
            $table->string('ma_code')->unique();
            $table->string('ten_ma_giam_gia');
            $table->text('mo_ta')->nullable();
            $table->enum('loai_giam_gia', ['phan_tram', 'so_tien']);
            $table->decimal('gia_tri_giam', 10, 2);
            $table->decimal('gia_tri_don_hang_toi_thieu', 10, 2)->default(0);
            $table->decimal('gia_tri_giam_toi_da', 10, 2)->nullable();
            $table->integer('so_luong')->default(1);
            $table->integer('da_su_dung')->default(0);
            $table->integer('gioi_han_su_dung_moi_user')->default(1);
            $table->boolean('trang_thai')->default(1);
            $table->datetime('ngay_bat_dau');
            $table->datetime('ngay_ket_thuc');
            $table->timestamps();
            
            $table->index(['ma_code', 'trang_thai']);
            $table->index(['ngay_bat_dau', 'ngay_ket_thuc']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ma_giam_gia');
    }
};