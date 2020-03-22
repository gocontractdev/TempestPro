<?php

namespace Tests\Feature;

use App\Permission;
use App\Role;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
            ->get( self::API_ROUTE . '/roles');

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
            ->post(self::API_ROUTE . '/roles',  $data);

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
            ->post(self::API_ROUTE . '/access/assign-permission',  $data);

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
            ->post(self::API_ROUTE . '/access/assign-permission',  $data);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonStructure([
            'status',
            'errors' => [
                'source_role_id',
                'target_role_id',
            ],
        ]);
    }

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
            ->post(self::API_ROUTE . '/access/assign-permission',  $data);
        $response->assertStatus(Response::HTTP_OK);
        $response = $this->actingAs($testUser, self::DRIVER)
            ->post(self::API_ROUTE . '/access/assign-permission',  $data);
        $response->assertStatus(Response::HTTP_OK);}
}
