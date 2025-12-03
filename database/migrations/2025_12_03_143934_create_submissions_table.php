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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hall_id')->constrained()->cascadeOnDelete();
            $table->string('booth_id');
            $table->string('booth_name')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('company_name')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['event_id', 'hall_id', 'booth_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
