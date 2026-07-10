<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Database\Seeders;

use Illuminate\Database\Seeder;
use Molitor\CmsPostRelations\Models\PostRelationType;
use Molitor\User\Exceptions\PermissionException;
use Molitor\User\Services\AclManagementService;

class CmsPostRelationsSeeder extends Seeder
{
    public function run(): void
    {
        try {
            /** @var AclManagementService $aclService */
            $aclService = app(AclManagementService::class);
            $aclService->createPermission('cms_post_relations', 'CMS bejegyzes kapcsolatok kezelese', 'admin', 'CMS');
        } catch (PermissionException $e) {
            $this->command?->error($e->getMessage());
        }

        $this->seedPostRelationTypes();
    }

    private function seedPostRelationTypes(): void
    {
        $types = [
            ['name' => 'Hasonlóság', 'slug' => 'hasonlosag'],
            ['name' => 'Kapcsolódó cikk', 'slug' => 'kapcsolodo-cikk'],
            ['name' => 'Ajánló', 'slug' => 'ajanlo'],
        ];

        foreach ($types as $type) {
            PostRelationType::firstOrCreate(
                ['slug' => $type['slug']],
                ['name' => $type['name']],
            );
        }
    }
}
