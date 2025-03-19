<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_all_groups_for_authenticated_user()
    {
        $user = User::factory()->create();
        $this->withSession(['user_id' => $user->id]);

        Group::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson(route('groups.index'), ['X-Session-ID' => session()->getId()]);

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_returns_unauthorized_if_session_is_invalid()
    {
        $response = $this->getJson(route('groups.index'), ['X-Session-ID' => 'invalid']);

        $response->assertStatus(401)
                 ->assertJson(['error' => 'Sesión inválida o expirada']);
    }

    /** @test */
    public function it_creates_a_new_group_with_valid_data()
    {
        $user = User::factory()->create();
        $this->withSession(['user_id' => $user->id]);

        $response = $this->postJson(route('groups.store'), [
            'title' => 'Grupo de Prueba',
            'color' => 'azul'
        ], ['X-Session-ID' => session()->getId()]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Grupo creado correctamente']);

        $this->assertDatabaseHas('groups', ['title' => 'Grupo de Prueba', 'user_id' => $user->id]);
    }

    /** @test */
    public function it_returns_validation_error_if_fields_are_missing()
    {
        $user = User::factory()->create();
        $this->withSession(['user_id' => $user->id]);

        $response = $this->postJson(route('groups.store'), [], ['X-Session-ID' => session()->getId()]);

        $response->assertStatus(400)
                 ->assertJsonStructure(['message', 'details']);
    }

    /** @test */
    public function it_updates_a_group_if_user_is_owner()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create(['user_id' => $user->id]);
        $this->withSession(['user_id' => $user->id]);

        $response = $this->putJson(route('groups.update', $group), [
            'title' => 'Nuevo Título',
            'color' => 'verde'
        ], ['X-Session-ID' => session()->getId()]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Grupo creado correctamente']);

        $this->assertDatabaseHas('groups', ['title' => 'Nuevo Título']);
    }

    /** @test */
    public function it_prevents_updating_group_if_not_owner()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $group = Group::factory()->create(['user_id' => $user1->id]);
        $this->withSession(['user_id' => $user2->id]);

        $response = $this->putJson(route('groups.update', $group), [
            'title' => 'Intento de cambio',
            'color' => 'rojo'
        ], ['X-Session-ID' => session()->getId()]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'No tienes permisos para editar este registro']);
    }

    /** @test */
    public function it_deletes_a_group_successfully()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create(['user_id' => $user->id]);
        $this->withSession(['user_id' => $user->id]);

        $response = $this->deleteJson(route('groups.destroy', $group), [], ['X-Session-ID' => session()->getId()]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Tipo eliminado correctamente']);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
