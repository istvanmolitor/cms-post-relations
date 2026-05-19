<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CmsPostRelationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'post_id' => $this->post_id,
            'post_title' => $this->post?->title,
            'related_post_id' => $this->related_post_id,
            'related_post_title' => $this->relatedPost?->title,
            'sort' => $this->sort,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}


