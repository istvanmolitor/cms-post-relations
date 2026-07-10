<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Molitor\User\Exceptions\PermissionException;
use Molitor\User\Exceptions\UserGroupException;
use Molitor\User\Models\Membership;
use Molitor\User\Services\AclManagementService;
use Tests\TestCase;

class PostRelationTypeApiTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsUserWithPermission(): User
    {
        /** @var AclManagementService $aclService */
        $aclService = app(AclManagementService::class);

        try {
            $aclService->createUserGroup('admin', 'Mindenhez van joga.');
        } catch (UserGroupException) {
            // already exists
        }

        try {
            $aclService->createPermission('cms_post_relations', 'CMS bejegyzes kapcsolatok kezelese', 'admin', 'CMS');
        } catch (PermissionException) {
            // already exists
        }

        $user = User::factory()->create();

        Membership::create([
            'user_group_id' => $aclService->getUserGroup('admin')->id,
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        return $user;
    }

    public function test_can_create_post_relation_type(): void
    {
        $this->actingAsUserWithPermission();

        $response = $this->postJson('/api/admin/post-relation-types', [
            'name' => 'Kapcsolódó cikkek',
            'slug' => 'kapcsolodo-cikkek',
            'description' => 'Rovid leiras',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.name', 'Kapcsolódó cikkek')
            ->assertJsonPath('data.slug', 'kapcsolodo-cikkek');

        $this->assertDatabaseHas('post_relation_types', [
            'name' => 'Kapcsolódó cikkek',
            'slug' => 'kapcsolodo-cikkek',
        ]);
    }

    public function test_cannot_create_post_relation_type_with_duplicate_slug(): void
    {
        $this->actingAsUserWithPermission();

        $payload = [
            'name' => 'Kapcsolódó cikkek',
            'slug' => 'kapcsolodo-cikkek',
            'description' => null,
        ];

        $this->postJson('/api/admin/post-relation-types', $payload)->assertCreated();

        $this->postJson('/api/admin/post-relation-types', $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['slug']);
    }

    public function test_can_update_post_relation_type(): void
    {
        $this->actingAsUserWithPermission();

        $created = $this->postJson('/api/admin/post-relation-types', [
            'name' => 'Eredeti',
            'slug' => 'eredeti',
            'description' => null,
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->putJson("/api/admin/post-relation-types/{$id}", [
            'name' => 'Modositott',
            'slug' => 'modositott',
            'description' => 'Uj leiras',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Modositott')
            ->assertJsonPath('data.slug', 'modositott');

        $this->assertDatabaseHas('post_relation_types', [
            'id' => $id,
            'name' => 'Modositott',
            'slug' => 'modositott',
        ]);
    }

    public function test_can_delete_post_relation_type(): void
    {
        $this->actingAsUserWithPermission();

        $created = $this->postJson('/api/admin/post-relation-types', [
            'name' => 'Torlendo',
            'slug' => 'torlendo',
            'description' => null,
        ])->assertCreated();

        $id = $created->json('data.id');

        $this->deleteJson("/api/admin/post-relation-types/{$id}")->assertNoContent();

        $this->assertDatabaseMissing('post_relation_types', ['id' => $id]);
    }
}
