<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class PostRelationType extends Model
{
    protected $table = 'post_relation_types';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected static function booted(): void
    {
        static::creating(function (PostRelationType $type): void {
            if (empty($type->slug)) {
                $type->slug = Str::slug($type->name);
            }
        });
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function postRelations(): HasMany
    {
        return $this->hasMany(PostRelation::class, 'post_relation_id');
    }
}
