<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Molitor\Cms\Models\Post;

class PostRelation extends Model
{
    protected $table = 'post_relations';

    protected $fillable = [
        'post_id',
        'related_post_id',
        'post_relation_type_id',
        'sort',
    ];

    protected $casts = [
        'post_id' => 'integer',
        'related_post_id' => 'integer',
        'post_relation_type_id' => 'integer',
        'sort' => 'float',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function relatedPost(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'related_post_id');
    }

    public function relationType(): BelongsTo
    {
        return $this->belongsTo(PostRelationType::class, 'post_relation_type_id');
    }
}
