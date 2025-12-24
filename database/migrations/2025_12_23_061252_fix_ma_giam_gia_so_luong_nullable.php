<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ma_giam_gia', function (Blueprint $table) {
            $table->integer('so_luong')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ma_giam_gia', function (Blueprint $table) {
            $table->integer('so_luong')->default(1)->change();
        });
    }
};