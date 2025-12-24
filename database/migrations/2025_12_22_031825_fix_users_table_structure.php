<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if columns need to be renamed
        $columns = Schema::getColumnListing('users');
        
        if (in_array('name', $columns) && !in_array('ho_ten', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('name', 'ho_ten');
            });
        }
        
        if (in_array('password', $columns) && !in_array('mat_khau', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('password', 'mat_khau');
            });
        }
        
        if (in_array('email_verified_at', $columns) && !in_array('xac_minh_email_luc', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('email_verified_at', 'xac_minh_email_luc');
            });
        }
        
        if (in_array('remember_token', $columns) && !in_array('token_ghi_nho', $columns)) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('remember_token', 'token_ghi_nho');
            });
        }
        
        // Add missing columns if they don't exist
        Schema::table('users', function (Blueprint $table) {
            $columns = Schema::getColumnListing('users');
            
            if (!in_array('so_dien_thoai', $columns)) {
                $table->string('so_dien_thoai')->nullable()->after('email');
            }
            
            if (!in_array('dia_chi', $columns)) {
                $table->text('dia_chi')->nullable()->after('so_dien_thoai');
            }
            
            if (!in_array('vai_tro', $columns)) {
                $table->enum('vai_tro', ['admin', 'customer'])->default('customer')->after('dia_chi');
            }
            
            if (!in_array('deleted_at', $columns)) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is designed to be safe and not reversible
        // to avoid data loss
    }
};