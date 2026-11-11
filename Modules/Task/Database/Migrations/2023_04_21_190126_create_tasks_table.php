<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 20)->default('todo');
            $table->unsignedTinyInteger('priority')->default(2);

            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->timestamp('remind_at')->nullable();
            $table->timestamp('reminded_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'status', 'position']);
            $table->index(['user_id', 'due_date']);
            $table->index(['remind_at', 'reminded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
