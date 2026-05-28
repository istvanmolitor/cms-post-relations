<?php

namespace Molitor\CmsPostRelations\Tests\Feature;

use Molitor\CmsPostRelations\Providers\CmsPostRelationsServiceProvider;
use Tests\TestCase;

class PackageSmokeTest extends TestCase
{
    public function test_service_provider_is_loaded(): void
    {
        $this->assertTrue(class_exists(CmsPostRelationsServiceProvider::class));
        $this->assertTrue($this->app->providerIsLoaded(CmsPostRelationsServiceProvider::class));
    }
}

