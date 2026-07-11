<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Repositories;

use Molitor\CmsPostRelations\Models\PostRelation;

interface PostRelationRepositoryInterface
{
    public function create(int $postId, int $relatedPostId, float $sort, ?int $relationTypeId = null): PostRelation;

    public function deleteByPostAndType(int $postId, ?int $relationTypeId = null): int;
}
