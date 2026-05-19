<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class CmsPostRelationsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->make(Router::class)
            ->prefix('api')
            ->group(__DIR__.'/../routes/api.php');
    }
}

