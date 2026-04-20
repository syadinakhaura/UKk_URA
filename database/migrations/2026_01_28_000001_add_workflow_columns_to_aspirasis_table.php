<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            $table->foreignId('submitted_by_admin_id')->nullable()->constrained('users')->nullOnDelete()->after('user_id');
            $table->timestamp('submitted_to_yayasan_at')->nullable()->after('status');
            $table->foreignId('validated_by_yayasan_id')->nullable()->constrained('users')->nullOnDelete()->after('submitted_by_admin_id');
            $table->timestamp('validated_at')->nullable()->after('submitted_to_yayasan_at');
            $table->text('catatan_yayasan')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('aspirasis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('submitted_by_admin_id');
            $table->dropConstrainedForeignId('validated_by_yayasan_id');
            $table->dropColumn([
                'submitted_to_yayasan_at',
                'validated_at',
                'catatan_yayasan',
            ]);
        });
    }
};

