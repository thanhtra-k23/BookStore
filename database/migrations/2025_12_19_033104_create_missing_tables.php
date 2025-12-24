<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Create the_loai table
        if (!Schema::hasTable('the_loai')) {
            Schema::create('the_loai', function (Blueprint $table) {
                $table->id('ma_the_loai');
                $table->string('ten_the_loai');
                $table->string('duong_dan')->unique();
                $table->text('mo_ta')->nullable();
                $table->unsignedBigInteger('ma_the_loai_cha')->nullable();
                $table->string('hinh_anh')->nullable();
                $table->boolean('trang_thai')->default(1);
                $table->integer('thu_tu_hien_thi')->default(0);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('ma_the_loai_cha')->references('ma_the_loai')->on('the_loai')->onDelete('set null');
                $table->index(['trang_thai', 'thu_tu_hien_thi']);
            });
        }

        // Create tac_gia table
        if (!Schema::hasTable('tac_gia')) {
            Schema::create('tac_gia', function (Blueprint $table) {
                $table->id('ma_tac_gia');
                $table->string('ten_tac_gia');
                $table->string('duong_dan')->unique();
                $table->text('tieu_su')->nullable();
                $table->date('ngay_sinh')->nullable();
                $table->date('ngay_mat')->nullable();
                $table->string('quoc_tich')->nullable();
                $table->string('website')->nullable();
                $table->string('email')->nullable();
                $table->string('so_dien_thoai')->nullable();
                $table->text('dia_chi')->nullable();
                $table->string('hinh_anh')->nullable();
                $table->boolean('trang_thai')->default(1);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['trang_thai']);
            });
        }

        // Create nha_xuat_ban table
        if (!Schema::hasTable('nha_xuat_ban')) {
            Schema::create('nha_xuat_ban', function (Blueprint $table) {
                $table->id('ma_nxb');
                $table->string('ten_nxb');
                $table->string('duong_dan')->unique();
                $table->text('dia_chi');
                $table->string('so_dien_thoai')->nullable();
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                $table->text('mo_ta')->nullable();
                $table->year('nam_thanh_lap')->nullable();
                $table->string('quoc_gia')->nullable();
                $table->string('logo')->nullable();
                $table->boolean('trang_thai')->default(1);
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index(['trang_thai']);
            });
        }

        // Add foreign keys to sach table if they don't exist
        Schema::table('sach', function (Blueprint $table) {
            if (!Schema::hasColumn('sach', 'ma_the_loai')) {
                $table->unsignedBigInteger('ma_the_loai')->nullable()->after('so_luot_danh_gia');
            }
            if (!Schema::hasColumn('sach', 'ma_tac_gia')) {
                $table->unsignedBigInteger('ma_tac_gia')->nullable()->after('ma_the_loai');
            }
            if (!Schema::hasColumn('sach', 'ma_nxb')) {
                $table->unsignedBigInteger('ma_nxb')->nullable()->after('ma_tac_gia');
            }
        });

        // Add foreign key constraints
        Schema::table('sach', function (Blueprint $table) {
            $table->foreign('ma_the_loai')->references('ma_the_loai')->on('the_loai')->onDelete('set null');
            $table->foreign('ma_tac_gia')->references('ma_tac_gia')->on('tac_gia')->onDelete('set null');
            $table->foreign('ma_nxb')->references('ma_nxb')->on('nha_xuat_ban')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('sach', function (Blueprint $table) {
            $table->dropForeign(['ma_the_loai']);
            $table->dropForeign(['ma_tac_gia']);
            $table->dropForeign(['ma_nxb']);
            $table->dropColumn(['ma_the_loai', 'ma_tac_gia', 'ma_nxb']);
        });

        Schema::dropIfExists('nha_xuat_ban');
        Schema::dropIfExists('tac_gia');
        Schema::dropIfExists('the_loai');
    }
};