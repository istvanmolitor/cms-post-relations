<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\DataTables;

use Molitor\Admin\DataTables\DataTable;
use Molitor\CmsPostRelations\Http\Resources\PostRelationTypeResource;
use Molitor\CmsPostRelations\Models\PostRelationType;

class PostRelationTypeDataTable extends DataTable
{
    protected function getModelClass(): string
    {
        return PostRelationType::class;
    }

    protected function getResourceClass(): string
    {
        return PostRelationTypeResource::class;
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
