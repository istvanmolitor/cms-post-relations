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
            $table->foreignId('post_relation_type_id')->constrained('post_relation_types')->restrictOnDelete();
            $table->float('sort')->default(0);
            $table->timestamps();

            $table->unique(['post_id', 'related_post_id', 'post_relation_type_id'], 'post_relations_unique');
            $table->index(['post_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_relations');
    }
};
