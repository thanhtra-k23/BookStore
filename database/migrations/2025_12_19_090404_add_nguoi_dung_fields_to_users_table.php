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
        Schema::table('users', function (Blueprint $table) {
            // Rename existing columns to match NguoiDung model
            $table->renameColumn('name', 'ho_ten');
            $table->renameColumn('password', 'mat_khau');
            $table->renameColumn('email_verified_at', 'xac_minh_email_luc');
            $table->renameColumn('remember_token', 'token_ghi_nho');
            
            // Add new columns
            $table->string('so_dien_thoai')->nullable()->after('email');
            $table->text('dia_chi')->nullable()->after('so_dien_thoai');
            $table->enum('vai_tro', ['admin', 'customer'])->default('customer')->after('dia_chi');
            
            // Add soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove added columns
            $table->dropSoftDeletes();
            $table->dropColumn(['so_dien_thoai', 'dia_chi', 'vai_tro']);
            
            // Rename columns back
            $table->renameColumn('ho_ten', 'name');
            $table->renameColumn('mat_khau', 'password');
            $table->renameColumn('xac_minh_email_luc', 'email_verified_at');
            $table->renameColumn('token_ghi_nho', 'remember_token');
        });
    }
};
