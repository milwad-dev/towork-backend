<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('description');
            $table->timestamp('remind_date')->nullable();
            $table->enum('priority', get_enum_values(TaskPriorityEnum::cases()));
            $table->enum('status', get_enum_values(TaskStatusEnum::cases()))->nullable();
            // TODO: Add label
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
