<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Repositories;

use Illuminate\Support\Str;
use Molitor\CmsPostRelations\Models\PostRelationType;

class PostRelationTypeRepository implements PostRelationTypeRepositoryInterface
{
    public function firstOrCreateBySlug(string $slug): PostRelationType
    {
        return PostRelationType::firstOrCreate(
            ['slug' => $slug],
            ['name' => Str::headline($slug)],
        );
    }
}
