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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('column_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->foreignId('after_card_id')
                ->nullable()
                ->constrained('cards')
                ->nullOnDelete();
            $table->string('name');
            $table->text('text')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
