<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('earning_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 20)->unique();
            $table->string('category')->default('bonus');
            $table->string('calculation_type')->default('fixed');
            $table->decimal('default_amount', 12, 2)->nullable();
            $table->decimal('default_rate', 5, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('earning_type_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->nullable();
            $table->decimal('rate', 5, 2)->nullable();
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('payslip_items', function (Blueprint $table) {
            $table->foreignId('earning_type_id')->nullable()->after('deduction_type_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payslip_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('earning_type_id');
        });

        Schema::dropIfExists('employee_earnings');
        Schema::dropIfExists('earning_types');
    }
};
