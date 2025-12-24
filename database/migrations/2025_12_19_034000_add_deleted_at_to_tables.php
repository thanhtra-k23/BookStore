<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add deleted_at to sach table for soft delete
        if (!Schema::hasColumn('sach', 'deleted_at')) {
            Schema::table('sach', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // Add deleted_at to other tables that might need soft delete
        $tables = ['the_loai', 'tac_gia', 'nha_xuat_ban', 'don_hang'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && !Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = ['sach', 'the_loai', 'tac_gia', 'nha_xuat_ban', 'don_hang'];
        
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'deleted_at')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};