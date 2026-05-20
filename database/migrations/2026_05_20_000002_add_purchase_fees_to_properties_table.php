<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('agency_fees', 10, 2)->nullable()->after('estimated_work_cost');
            $table->decimal('bank_fees', 10, 2)->nullable()->after('agency_fees');
            $table->decimal('loan_guarantee_fees', 10, 2)->nullable()->after('bank_fees');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['agency_fees', 'bank_fees', 'loan_guarantee_fees']);
        });
    }
};
