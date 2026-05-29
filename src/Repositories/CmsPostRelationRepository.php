<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Repositories;

use Molitor\CmsPostRelations\Models\CmsPostRelation;

class CmsPostRelationRepository implements CmsPostRelationRepositoryInterface
{
    private CmsPostRelation $cmsPostRelation;

    public function __construct()
    {
        $this->cmsPostRelation = new CmsPostRelation;
    }

    public function create(int $postId, int $relatedPostId, int $sort): CmsPostRelation
    {
        return $this->cmsPostRelation->query()->create([
            'post_id' => $postId,
            'related_post_id' => $relatedPostId,
            'sort' => $sort,
        ]);
    }
}

