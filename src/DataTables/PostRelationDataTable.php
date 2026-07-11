<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\DataTables;

use Molitor\Admin\DataTables\DataTable;
use Molitor\CmsPostRelations\Http\Resources\PostRelationResource;
use Molitor\CmsPostRelations\Models\PostRelation;

class PostRelationDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return PostRelation::class;
    }

    protected function getResourceClass(): string
    {
        return PostRelationResource::class;
    }

    protected function getSearchPlaceholder(): string
    {
        return 'Keresés név alapján...';
    }

    protected function initColumns(): void
    {
        $this->addColumn('name')->setLabel('Név')->setSearchable()->setOrderable();
        $this->addColumn('slug')->setLabel('Slug')->setSearchable()->setOrderable();
        $this->addColumn('description')->setLabel('Leírás');
    }
}
