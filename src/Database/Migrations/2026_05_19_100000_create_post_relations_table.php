<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_relations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('related_post_id')->constrained('posts')->cascadeOnDelete();
            $table->foreignId('post_relation_id')->nullable()->constrained('post_relation_types')->nullOnDelete();
            $table->float('sort')->default(0);
            $table->timestamps();

            $table->unique(['post_id', 'related_post_id']);
            $table->index(['post_id', 'sort']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_relations');
    }
};
