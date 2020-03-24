<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SimpleTest extends TestCase
{
    use RefreshDatabase;

    const API_ROUTE = '/api';

    const DRIVER = 'api';

    public function testIndex()
    {
        $testUser = factory(User::class)->create([]);
        $response = $this->actingAs($testUser, self::DRIVER)
            ->get( route('roles.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(3);
        $response->assertJsonStructure([ 'data', 'links', 'meta',]);
    }

    public function testStore()
    {
        $monitorId =  rand(1, 100);
        $testUser = factory(User::class)->create(['id' => $monitorId,]);
        $faker = Factory::create();
        $data = [
            'name' => $faker->name,
            'description' => $faker->text,
            'uuid' => $faker->uuid,
        ];

        $response = $this->actingAs($testUser, self::DRIVER)
            ->post(route('roles.store'),  $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'name',
                'description',
                'uuid',
            ]
        ]);
        $response->assertJsonFragment([
            'name' => $data['name'],
            'description' => $data['description'],
            'uuid' => $data['uuid'],
        ]);
    }

    public function testAssignPermissionSuccess()
    {
        $monitorId =  rand(1, 100);
        $testUser = factory(User::class)->create(['id' => $monitorId,]);
        $roles = factory(Role::class, 5)->create();
        $permission =  factory(Permission::class)->create(['id' => rand(),]);

        $data = [
            'source_role_id' => $roles[0]->id,
            'target_role_id' => $roles[1]->id,
            'permission_key' => $permission->key,
        ];
        $response = $this->actingAs($testUser, self::DRIVER)
            ->post(route('assign.permission'),  $data);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'source_role_id',
                'target_role_id',
                'permission_id',
            ]
        ]);
        $response->assertJsonFragment([
            'source_role_id' => $roles[0]->id,
            'target_role_id' => $roles[1]->id,
            'permission_id' => $permission->id
        ]);
    }

    public function testBulkAssignPermission()
    {
        $monitorId =  rand(1, 100);
        $testUser = factory(User::class)->create(['id' => $monitorId,]);
        $roles = factory(Role::class, 5)->create();
        $permissions =  factory(Permission::class, 2)->create();
        // test 1
        $data = [
            $permissions[0]->key => [$roles[2]->id,],
        ];
        $response = $this->actingAs($testUser, self::DRIVER)
            ->put(route('roles.bulk', ['role' => $roles[1]->id,]),  $data);
            //->put(self::API_ROUTE . '/roles/' . $roles[1]->id . '/assign',  $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'success_count',
                'failure_count',
            ]
        ]);
        $response->assertJsonFragment([
            'success_count' => 1,
            'failure_count' => 0,
        ]);

        // test 2
        $data = [
            $permissions[0]->key => [$roles[1]->id, $roles[2]->id, $roles[4]->id],
            $permissions[1]->key => [$roles[4]->id, $roles[3]->id, $roles[0]->id],
        ];

        $response = $this->actingAs($testUser, self::DRIVER)
            ->withoutExceptionHandling()
            ->put(route('roles.bulk', ['role' => $roles[1]->id,]),  $data);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'success_count',
                'failure_count',
            ]
        ]);
        $response->assertJsonFragment([
            'success_count' => 5,
            'failure_count' => 1,
        ]);
    }

    public function testAssignPermissionSamePermissionFailure()
    {
        $monitorId =  rand(1, 100);
        $testUser = factory(User::class)->create(['id' => $monitorId,]);
        $roles = factory(Role::class, 5)->create();
        $permission =  factory(Permission::class)->create(['id' => rand(),]);

        $data = [
            'source_role_id' => $roles[0]->id,
            'target_role_id' => $roles[0]->id,
            'permission_key' => $permission->key,
        ];
        $response = $this->actingAs($testUser, self::DRIVER)
            ->post(route('assign.permission'),  $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'errors' => [
                'source_role_id',
                'target_role_id',
            ],
        ]);
    }

    /**
     * @test test if the repeating same combination fails or returns previously created interaction model
     */
    public function testAssignPermissionDuplication()
    {
        $monitorId =  rand(1, 100);
        $testUser = factory(User::class)->create(['id' => $monitorId,]);
        $roles = factory(Role::class, 5)->create();
        $permission =  factory(Permission::class)->create(['id' => rand(),]);
        $data = [
            'source_role_id' => $roles[0]->id,
            'target_role_id' => $roles[1]->id,
            'permission_key' => $permission->key,
        ];
        $response = $this->actingAs($testUser, self::DRIVER)
            ->post(route('assign.permission'),  $data);
        $response->assertStatus(Response::HTTP_OK);
        $response = $this->actingAs($testUser, self::DRIVER)
            ->post(route('assign.permission'),  $data);
        $response->assertStatus(Response::HTTP_OK);}
}
