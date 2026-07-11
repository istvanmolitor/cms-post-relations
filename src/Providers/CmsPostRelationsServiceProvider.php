<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Molitor\CmsPostRelations\Repositories\PostRelationRepository;
use Molitor\CmsPostRelations\Repositories\PostRelationRepositoryInterface;
use Molitor\CmsPostRelations\Repositories\PostRelationTypeRepository;
use Molitor\CmsPostRelations\Repositories\PostRelationTypeRepositoryInterface;

class CmsPostRelationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            PostRelationRepositoryInterface::class,
            PostRelationRepository::class,
        );

        $this->app->bind(
            PostRelationTypeRepositoryInterface::class,
            PostRelationTypeRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->make(Router::class)
            ->prefix('api')
            ->group(__DIR__.'/../routes/api.php');
    }
}
