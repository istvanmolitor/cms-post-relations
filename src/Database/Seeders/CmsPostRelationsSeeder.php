<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Database\Seeders;

use Illuminate\Database\Seeder;
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
    }
}
