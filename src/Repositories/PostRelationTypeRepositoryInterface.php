<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Repositories;

use Molitor\CmsPostRelations\Models\PostRelationType;

interface PostRelationTypeRepositoryInterface
{
    public function firstOrCreateBySlug(string $slug): PostRelationType;
}
