<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Molitor\Cms\Models\Content;
use Molitor\Cms\Models\Post;
use Molitor\CmsPostRelations\Models\PostRelationType;
use Tests\TestCase;

class PostRelationApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_post_to_post_relation(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = $this->createPost();
        $relatedPost = $this->createPost('Kapcsolt poszt');
        $type = $this->createRelationType();

        $response = $this->postJson('/api/admin/post-relations', [
            'post_id' => $post->id,
            'related_post_id' => $relatedPost->id,
            'sort' => 5,
            'post_relation_type_ids' => [$type->id],
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.0.post_id', $post->id)
            ->assertJsonPath('data.0.related_post_id', $relatedPost->id)
            ->assertJsonPath('data.0.sort', 5);

        $this->assertDatabaseHas('post_relations', [
            'post_id' => $post->id,
            'related_post_id' => $relatedPost->id,
            'post_relation_type_id' => $type->id,
            'sort' => 5,
        ]);
    }

    public function test_can_attach_related_post_with_multiple_types(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = $this->createPost();
        $relatedPost = $this->createPost('Kapcsolt poszt');
        $firstType = $this->createRelationType('Hasonlóság');
        $secondType = $this->createRelationType('Ajánló');

        $response = $this->postJson('/api/admin/post-relations', [
            'post_id' => $post->id,
            'related_post_id' => $relatedPost->id,
            'sort' => 0,
            'post_relation_type_ids' => [$firstType->id, $secondType->id],
        ]);

        $response->assertCreated();
        $this->assertCount(2, $response->json('data'));

        $this->assertDatabaseHas('post_relations', [
            'post_id' => $post->id,
            'related_post_id' => $relatedPost->id,
            'post_relation_type_id' => $firstType->id,
        ]);
        $this->assertDatabaseHas('post_relations', [
            'post_id' => $post->id,
            'related_post_id' => $relatedPost->id,
            'post_relation_type_id' => $secondType->id,
        ]);
    }

    public function test_cannot_create_duplicate_post_to_post_relation_with_same_type(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $post = $this->createPost();
        $relatedPost = $this->createPost('Masodik poszt');
        $type = $this->createRelationType();

        $payload = [
            'post_id' => $post->id,
            'related_post_id' => $relatedPost->id,
            'sort' => 0,
            'post_relation_type_ids' => [$type->id],
        ];

        $this->postJson('/api/admin/post-relations', $payload)->assertCreated();

        $this->postJson('/api/admin/post-relations', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['post_relation_type_ids.0']);
    }

    private function createPost(string $title = 'Teszt poszt'): Post
    {
        $content = Content::query()->create();

        return Post::query()->create([
            'title' => $title,
            'slug' => 'teszt-poszt-'.uniqid(),
            'is_published' => true,
            'lead' => 'Rovid leiras',
            'layout' => 'default',
            'main_image_url' => null,
            'content_id' => $content->id,
            'language_id' => null,
        ]);
    }

    private function createRelationType(string $name = 'Kapcsolódó cikk'): PostRelationType
    {
        return PostRelationType::query()->create([
            'name' => $name,
        ]);
    }
}
