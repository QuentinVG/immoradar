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
        Schema::create('property_checklist_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visit_checklist_question_id')->constrained()->cascadeOnDelete();
            $table->string('answer')->default('unknown');
            $table->integer('score')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['property_id', 'visit_checklist_question_id'], 'property_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_checklist_answers');
    }
};
