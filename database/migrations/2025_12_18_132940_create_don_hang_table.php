<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id('ma_don_hang');
            $table->string('ma_don_hang_unique')->unique();
            $table->unsignedBigInteger('ma_nguoi_dung');
            $table->decimal('tong_tien', 12, 2);
            $table->decimal('tien_giam_gia', 12, 2)->default(0);
            $table->decimal('phi_van_chuyen', 10, 2)->default(0);
            $table->decimal('tong_thanh_toan', 12, 2);
            $table->enum('trang_thai', ['cho_xac_nhan', 'da_xac_nhan', 'dang_chuan_bi', 'dang_giao', 'da_giao', 'da_huy'])->default('cho_xac_nhan');
            $table->enum('phuong_thuc_thanh_toan', ['cod', 'bank_transfer', 'momo', 'vnpay'])->default('cod');
            $table->enum('trang_thai_thanh_toan', ['chua_thanh_toan', 'da_thanh_toan', 'da_hoan'])->default('chua_thanh_toan');
            $table->string('ten_nguoi_nhan');
            $table->string('so_dien_thoai_nguoi_nhan');
            $table->text('dia_chi_giao_hang');
            $table->text('ghi_chu')->nullable();
            $table->unsignedBigInteger('ma_giam_gia')->nullable();
            $table->timestamp('ngay_dat_hang')->useCurrent();
            $table->timestamp('ngay_giao_hang')->nullable();
            $table->timestamps();
            
            $table->foreign('ma_nguoi_dung')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ma_giam_gia')->references('ma_giam_gia')->on('ma_giam_gia')->onDelete('set null');
            
            $table->index(['ma_nguoi_dung', 'trang_thai']);
            $table->index(['trang_thai', 'ngay_dat_hang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};