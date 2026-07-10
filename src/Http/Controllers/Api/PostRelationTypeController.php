<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Molitor\CmsPostRelations\DataTables\PostRelationTypeDataTable;
use Molitor\CmsPostRelations\Http\Requests\PostRelationType\StorePostRelationTypeRequest;
use Molitor\CmsPostRelations\Http\Requests\PostRelationType\UpdatePostRelationTypeRequest;
use Molitor\CmsPostRelations\Http\Resources\PostRelationTypeResource;
use Molitor\CmsPostRelations\Models\PostRelationType;

class PostRelationTypeController
{
    public function index(PostRelationTypeDataTable $dataTable): AnonymousResourceCollection
    {
        return $dataTable->getResponse();
    }

    public function store(StorePostRelationTypeRequest $request): JsonResponse
    {
        $type = PostRelationType::create($request->validated());

        return response()->json([
            'data' => new PostRelationTypeResource($type),
        ], 201);
    }

    public function show(PostRelationType $postRelationType): JsonResponse
    {
        return response()->json([
            'data' => new PostRelationTypeResource($postRelationType),
        ]);
    }

    public function update(UpdatePostRelationTypeRequest $request, PostRelationType $postRelationType): JsonResponse
    {
        $postRelationType->update($request->validated());

        return response()->json([
            'data' => new PostRelationTypeResource($postRelationType),
        ]);
    }

    public function destroy(PostRelationType $postRelationType): JsonResponse
    {
        $postRelationType->delete();

        return response()->json(null, 204);
    }
}
