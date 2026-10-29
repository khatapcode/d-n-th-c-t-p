<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->after('student_id');
            $table->string('note')->nullable()->after('status');
            $table->timestamp('decided_at')->nullable()->after('note');
            $table->timestamps();
        });

        // Các đăng ký đã có từ trước được xem là đã duyệt.
        DB::table('class_student')->update([
            'status' => 'approved',
            'decided_at' => now(),
            'enrolled_at' => DB::raw('COALESCE(enrolled_at, NOW())'),
        ]);

        Schema::table('invoices', function (Blueprint $table) {
            $table->string('paid_note')->nullable()->after('method');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('paid_note');
        });

        Schema::table('class_student', function (Blueprint $table) {
            $table->dropColumn(['status', 'note', 'decided_at', 'created_at', 'updated_at']);
        });
    }
};
