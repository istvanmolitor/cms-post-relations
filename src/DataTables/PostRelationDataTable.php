<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Molitor\Admin\DataTables\DataTable;
use Molitor\Cms\Models\Post;
use Molitor\CmsPostRelations\Http\Resources\PostRelationResource;
use Molitor\CmsPostRelations\Models\PostRelation;
use Molitor\CmsPostRelations\Models\PostRelationType;

class PostRelationDataTable extends DataTable
{
    public function __construct(
        protected Post $post,
        protected PostRelationType $type,
        Request $request,
    ) {
        parent::__construct($request);
    }

    protected function getModelClass(): string
    {
        return PostRelation::class;
    }

    protected function getResourceClass(): string
    {
        return PostRelationResource::class;
    }

    protected function initColumns(): void
    {
        $this->addColumn('related_post_title')->setLabel('Kapcsolt poszt');
        $this->addColumn('sort')->setLabel('Sorrend')->setOrderable();
    }

    public function query(Builder $query): Builder
    {
        return $query
            ->with(['relatedPost:id,title,main_image_url'])
            ->where('post_id', $this->post->id)
            ->where('post_relation_type_id', $this->type->id);
    }
}
