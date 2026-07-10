<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Molitor\CmsPostRelations\Http\Controllers\Api\PostRelationController;
use Molitor\CmsPostRelations\Http\Controllers\Api\PostRelationTypeController;

Route::prefix('admin')
    ->middleware(['api', 'auth:sanctum', 'permission:cms_post_relations'])
    ->name('post-relations.admin.')
    ->group(function (): void {
        Route::get('post-relations/options', [PostRelationController::class, 'options'])->name('options');
        Route::apiResource('post-relations', PostRelationController::class);
        Route::apiResource('post-relation-types', PostRelationTypeController::class);
    });
