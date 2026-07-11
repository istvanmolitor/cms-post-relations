<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Repositories;

use Molitor\CmsPostRelations\Models\PostRelation;

class PostRelationRepository implements PostRelationRepositoryInterface
{
    private PostRelation $postRelation;

    public function __construct()
    {
        $this->postRelation = new PostRelation;
    }

    public function create(int $postId, int $relatedPostId, float $sort, ?int $relationTypeId = null): PostRelation
    {
        return $this->postRelation->query()->create([
            'post_id' => $postId,
            'related_post_id' => $relatedPostId,
            'sort' => $sort,
            'post_relation_type_id' => $relationTypeId,
        ]);
    }
}
