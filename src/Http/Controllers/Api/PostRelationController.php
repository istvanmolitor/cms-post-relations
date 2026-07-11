<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Molitor\Cms\Models\Post;
use Molitor\CmsPostRelations\DataTables\PostRelationDataTable;
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
    public function index(Request $request): AnonymousResourceCollection
    {
        $post = Post::findOrFail($request->integer('post_id'));
        $type = PostRelationType::findOrFail($request->integer('post_relation_type_id'));

        $dataTable = new PostRelationDataTable($post, $type, $request);

        return $dataTable->getResponse();
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
            (int) $request->integer('post_relation_type_id'),
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
            'post_relation_type_id' => $request->filled('post_relation_type_id') ? (int) $request->integer('post_relation_type_id') : null,
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
