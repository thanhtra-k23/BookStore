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
        Schema::table('the_loai', function (Blueprint $table) {
            if (!Schema::hasColumn('the_loai', 'ma_the_loai_cha')) {
                $table->unsignedBigInteger('ma_the_loai_cha')->nullable()->after('mo_ta');
            }
            if (!Schema::hasColumn('the_loai', 'trang_thai')) {
                $table->boolean('trang_thai')->default(true)->after('ma_the_loai_cha');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('the_loai', function (Blueprint $table) {
            if (Schema::hasColumn('the_loai', 'ma_the_loai_cha')) {
                $table->dropColumn('ma_the_loai_cha');
            }
            if (Schema::hasColumn('the_loai', 'trang_thai')) {
                $table->dropColumn('trang_thai');
            }
        });
    }
};
