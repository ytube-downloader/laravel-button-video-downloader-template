<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->id();
            $table->string('conversion_id')->unique();
            $table->string('original_filename');
            $table->string('original_format');
            $table->string('target_format');
            $table->string('quality')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('output_path')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('conversion_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversions');
    }
};