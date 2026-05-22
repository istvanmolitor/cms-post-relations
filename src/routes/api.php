<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Molitor\CmsPostRelations\Http\Controllers\Api\CmsPostRelationController;

Route::prefix('admin')
    ->middleware(['api', 'auth:sanctum'])
    ->name('cms-post-relations.admin.')
    ->group(function (): void {
        Route::get('cms-post-relations/options', [CmsPostRelationController::class, 'options'])->name('options');
        Route::apiResource('cms-post-relations', CmsPostRelationController::class);
    });
