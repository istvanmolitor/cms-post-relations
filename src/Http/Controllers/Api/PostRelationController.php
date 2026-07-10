<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Molitor\Admin\Http\Resources\DataTableResource;
use Molitor\Cms\Models\Post;
use Molitor\CmsPostRelations\Http\Requests\StorePostRelationRequest;
use Molitor\CmsPostRelations\Http\Requests\UpdatePostRelationRequest;
use Molitor\CmsPostRelations\Http\Resources\PostRelationResource;
use Molitor\CmsPostRelations\Models\PostRelation;
use Molitor\CmsPostRelations\Models\PostRelationType;
use Molitor\CmsPostRelations\Repositories\PostRelationRepositoryInterface;

class PostRelationController
{
    public function __construct(
        private PostRelationRepositoryInterface $postRelationRepository
    ) {}
    public function index(Request $request): JsonResponse
    {
        $query = PostRelation::query()
            ->with(['post:id,title', 'relatedPost:id,title,main_image_url', 'relationType:id,name']);

        $postId = $request->input('post_id');

        if ($postId !== null && $postId !== '') {
            $query->where('post_id', (int) $postId);
        }

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
            PostRelationResource::class,
            $request->only(['search', 'sort', 'direction', 'post_id']),
            [
                ['key' => 'related_post_title', 'label' => 'Kapcsolt poszt'],
                ['key' => 'post_relation_type_name', 'label' => 'Kapcsolat típusa'],
                ['key' => 'sort', 'label' => 'Sorrend', 'sortable' => true],
            ]
        ));
    }

    public function options(): JsonResponse
    {
        $posts = Post::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        $relationTypes = PostRelationType::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'posts' => $posts,
            'relation_types' => $relationTypes,
        ]);
    }

    public function store(StorePostRelationRequest $request): JsonResponse
    {
        $relation = $this->postRelationRepository->create(
            (int) $request->integer('post_id'),
            (int) $request->integer('related_post_id'),
            (float) $request->input('sort', 0),
            $request->filled('post_relation_id') ? (int) $request->integer('post_relation_id') : null,
        );

        $relation->load(['post:id,title', 'relatedPost:id,title,main_image_url', 'relationType:id,name']);

        return response()->json([
            'data' => new PostRelationResource($relation),
        ], 201);
    }

    public function show(PostRelation $postRelation): JsonResponse
    {
        $postRelation->load(['post:id,title', 'relatedPost:id,title,main_image_url', 'relationType:id,name']);

        return response()->json([
            'data' => new PostRelationResource($postRelation),
        ]);
    }

    public function update(UpdatePostRelationRequest $request, PostRelation $postRelation): JsonResponse
    {
        $postRelation->update([
            'post_id' => (int) $request->integer('post_id'),
            'related_post_id' => (int) $request->integer('related_post_id'),
            'sort' => (float) $request->input('sort', 0),
            'post_relation_id' => $request->filled('post_relation_id') ? (int) $request->integer('post_relation_id') : null,
        ]);

        $postRelation->load(['post:id,title', 'relatedPost:id,title,main_image_url', 'relationType:id,name']);

        return response()->json([
            'data' => new PostRelationResource($postRelation),
        ]);
    }

    public function destroy(PostRelation $postRelation): JsonResponse
    {
        $postRelation->delete();

        return response()->json(null, 204);
    }
}
