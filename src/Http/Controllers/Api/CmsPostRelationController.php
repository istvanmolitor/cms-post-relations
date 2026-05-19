<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Molitor\Admin\Http\Resources\DataTableResource;
use Molitor\Cms\Models\Post;
use Molitor\CmsPostRelations\Http\Requests\StoreCmsPostRelationRequest;
use Molitor\CmsPostRelations\Http\Requests\UpdateCmsPostRelationRequest;
use Molitor\CmsPostRelations\Http\Resources\CmsPostRelationResource;
use Molitor\CmsPostRelations\Models\CmsPostRelation;

class CmsPostRelationController
{
    public function index(Request $request): JsonResponse
    {
        $query = CmsPostRelation::query()
            ->with(['post:id,title', 'relatedPost:id,title']);

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($builder) use ($search): void {
                $builder->whereHas('post', function ($postQuery) use ($search): void {
                    $postQuery->where('title', 'like', '%'.$search.'%');
                })->orWhereHas('relatedPost', function ($relatedPostQuery) use ($search): void {
                    $relatedPostQuery->where('title', 'like', '%'.$search.'%');
                });
            });
        }

        $allowedSortFields = ['id', 'post_id', 'related_post_id', 'sort', 'created_at'];
        $sort = $request->input('sort', 'sort');
        $direction = $request->input('direction', 'asc') === 'desc' ? 'desc' : 'asc';

        if (in_array($sort, $allowedSortFields, true)) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('sort')->orderBy('id');
        }

        $relations = $query
            ->paginate((int) $request->input('per_page', 10))
            ->withQueryString();

        return response()->json(new DataTableResource(
            $relations,
            CmsPostRelationResource::class,
            $request->only(['search', 'sort', 'direction'])
        ));
    }

    public function options(): JsonResponse
    {
        $posts = Post::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        return response()->json([
            'posts' => $posts,
        ]);
    }

    public function store(StoreCmsPostRelationRequest $request): JsonResponse
    {
        $relation = CmsPostRelation::query()->create([
            'post_id' => (int) $request->integer('post_id'),
            'related_post_id' => (int) $request->integer('related_post_id'),
            'sort' => (int) $request->integer('sort', 0),
        ]);

        $relation->load(['post:id,title', 'relatedPost:id,title']);

        return response()->json([
            'data' => new CmsPostRelationResource($relation),
        ], 201);
    }

    public function show(CmsPostRelation $cmsPostRelation): JsonResponse
    {
        $cmsPostRelation->load(['post:id,title', 'relatedPost:id,title']);

        return response()->json([
            'data' => new CmsPostRelationResource($cmsPostRelation),
        ]);
    }

    public function update(UpdateCmsPostRelationRequest $request, CmsPostRelation $cmsPostRelation): JsonResponse
    {
        $cmsPostRelation->update([
            'post_id' => (int) $request->integer('post_id'),
            'related_post_id' => (int) $request->integer('related_post_id'),
            'sort' => (int) $request->integer('sort', 0),
        ]);

        $cmsPostRelation->load(['post:id,title', 'relatedPost:id,title']);

        return response()->json([
            'data' => new CmsPostRelationResource($cmsPostRelation),
        ]);
    }

    public function destroy(CmsPostRelation $cmsPostRelation): JsonResponse
    {
        $cmsPostRelation->delete();

        return response()->json(null, 204);
    }
}


