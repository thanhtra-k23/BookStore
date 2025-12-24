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
        Schema::table('sach', function (Blueprint $table) {
            $table->integer('nam_xuat_ban')->nullable()->after('ngay_xuat_ban');
            $table->string('trang_thai', 20)->default('active')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sach', function (Blueprint $table) {
            $table->dropColumn('nam_xuat_ban');
            $table->boolean('trang_thai')->default(1)->change();
        });
    }
};