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
        Schema::create('visit_checklist_questions', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('question');
            $table->text('help_text')->nullable();
            $table->unsignedTinyInteger('weight')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_checklist_questions');
    }
};
