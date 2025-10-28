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
        Schema::create('tasks_1s', function (Blueprint $table) {
            $table->id();  
            $table->foreignId('user_id')->constrained();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->text('status')->nullable();
            $table->text('priority')->nullable();
            $table->boolean('isCompleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks_1s');
    }
};
