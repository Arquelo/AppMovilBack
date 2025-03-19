<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TypeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $sessionId;

    public function setUp(): void
    {
        parent::setUp();
        // Crear un usuario y simular la sesión
        $this->user = User::factory()->create();
        $this->sessionId = 'test-session-id';
        session()->setId($this->sessionId);
        session()->start();
        session(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_returns_all_types_for_authenticated_user()
    {
        Type::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->getJson(route('types.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function it_returns_error_if_user_not_authenticated()
    {
        $response = $this->getJson(route('types.index'));

        $response->assertStatus(401)
            ->assertJson(['error' => 'Sesión inválida o expirada']);
    }

    /** @test */
    public function it_stores_a_new_type()
    {
        $data = ['type' => 'Test Type'];

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->postJson(route('types.store'), $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Registro creado correctamente']);

        $this->assertDatabaseHas('types', [
            'type' => 'Test Type',
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->postJson(route('types.store'), []);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Error al procesar los datos',
                'details' => ['El campo Tipo es requerido']
            ]);
    }

    /** @test */
    public function it_shows_a_specific_type()
    {
        $type = Type::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('types.show', $type));

        $response->assertStatus(200)
            ->assertJson([
                'id' => $type->id,
                'type' => $type->type
            ]);
    }

    /** @test */
    public function it_updates_a_type()
    {
        $type = Type::factory()->create(['user_id' => $this->user->id]);

        $data = ['type' => 'Updated Type'];

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->putJson(route('types.update', $type), $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Registro creado correctamente']);

        $this->assertDatabaseHas('types', [
            'id' => $type->id,
            'type' => 'Updated Type'
        ]);
    }

    /** @test */
    public function it_does_not_allow_updating_another_users_type()
    {
        $otherUser = User::factory()->create();
        $type = Type::factory()->create(['user_id' => $otherUser->id]);

        $data = ['type' => 'Unauthorized Update'];

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->putJson(route('types.update', $type), $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'No tienes permisos para editar este registro']);
    }

    /** @test */
    public function it_deletes_a_type()
    {
        $type = Type::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(route('types.destroy', $type));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Registro eliminado correctamente']);

        $this->assertDatabaseMissing('types', ['id' => $type->id]);
    }
}
