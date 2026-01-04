<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Xóa cột parent_id dư thừa, chỉ giữ lại ma_the_loai_cha
     */
    public function up(): void
    {
        Schema::table('the_loai', function (Blueprint $table) {
            if (Schema::hasColumn('the_loai', 'parent_id')) {
                $table->dropColumn('parent_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('the_loai', function (Blueprint $table) {
            if (!Schema::hasColumn('the_loai', 'parent_id')) {
                $table->unsignedBigInteger('parent_id')->nullable();
            }
        });
    }
};
