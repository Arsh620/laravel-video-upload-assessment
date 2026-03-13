<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('upload_id')->unique();
            $table->string('filename');
            $table->string('original_name');
            $table->bigInteger('total_size');
            $table->integer('total_chunks');
            $table->integer('uploaded_chunks')->default(0);
            $table->json('chunk_status')->nullable();
            $table->enum('status', ['uploading', 'processing', 'completed', 'failed'])->default('uploading');
            $table->string('s3_key')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_uploads');
    }
};