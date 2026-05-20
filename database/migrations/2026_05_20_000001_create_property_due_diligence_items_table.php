<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_due_diligence_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->string('label');
            $table->text('why');
            $table->text('action')->nullable();
            $table->string('status')->default('unknown');
            $table->boolean('is_blocking')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['property_id', 'key']);
            $table->index(['property_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_due_diligence_items');
    }
};
