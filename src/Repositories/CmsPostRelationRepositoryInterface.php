<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Repositories;

use Molitor\CmsPostRelations\Models\CmsPostRelation;

interface CmsPostRelationRepositoryInterface
{
    public function create(int $postId, int $relatedPostId, int $sort): CmsPostRelation;
}

