<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('employment_type')->default('full_time')->after('employment_status');
            $table->string('compensation_type')->default('salary')->after('employment_type');
            $table->decimal('hourly_rate', 10, 2)->nullable()->after('compensation_type');
            $table->decimal('base_salary', 12, 2)->nullable()->after('hourly_rate');
            $table->string('bank_routing_number')->nullable()->after('bank_account_number');
            $table->decimal('overtime_multiplier', 4, 2)->default(1.5)->after('tax_id');
            $table->decimal('pto_balance', 6, 2)->default(0)->after('overtime_multiplier');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('notes');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('is_approved');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });

        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false)->after('notes');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->after('is_approved');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });

        Schema::create('pay_period_settings', function (Blueprint $table) {
            $table->id();
            $table->string('frequency')->default('monthly');
            $table->unsignedTinyInteger('overtime_threshold_hours')->default(40);
            $table->decimal('default_overtime_multiplier', 4, 2)->default(1.5);
            $table->timestamps();
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('leave_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('days_requested', 5, 2);
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('payroll_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_audit_logs');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('pay_period_settings');

        Schema::table('payroll_runs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn(['is_approved', 'approved_at']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approved_by');
            $table->dropColumn(['is_approved', 'approved_at']);
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'employment_type',
                'compensation_type',
                'hourly_rate',
                'base_salary',
                'bank_routing_number',
                'overtime_multiplier',
                'pto_balance',
            ]);
        });
    }
};
