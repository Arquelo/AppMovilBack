<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Note;
use App\Models\Type;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $sessionId;
    protected $type;
    protected $group;

    public function setUp(): void
    {
        parent::setUp();
        // Crear un usuario y simular la sesión
        $this->user = User::factory()->create();
        $this->sessionId = 'test-session-id';
        session()->setId($this->sessionId);
        session()->start();
        session(['user_id' => $this->user->id]);

        // Crear un tipo y un grupo para asociar las notas
        $this->type = Type::factory()->create(['user_id' => $this->user->id]);
        $this->group = Group::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function it_returns_all_notes_for_authenticated_user()
    {
        Note::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ]);

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->getJson(route('notes.index'));

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    /** @test */
    public function it_returns_error_if_user_not_authenticated()
    {
        $response = $this->getJson(route('notes.index'));

        $response->assertStatus(401)
            ->assertJson(['error' => 'Sesion inválida o expirada.']);
    }

    /** @test */
    public function it_returns_all_types_and_groups_on_create()
    {
        $response = $this->getJson(route('notes.create'));

        $response->assertStatus(200)
            ->assertJsonStructure(['types', 'groups']);
    }

    /** @test */
    public function it_stores_a_new_note()
    {
        $data = [
            'description' => 'Test Note',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ];

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->postJson(route('notes.store'), $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Producto creado correctamente']);

        $this->assertDatabaseHas('notes', [
            'description' => 'Test Note',
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->postJson(route('notes.store'), []);

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Error al procesar los datos',
                'details' => ['El campo Tipo es requerido']
            ]);
    }

    /** @test */
    public function it_shows_a_specific_note()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ]);

        $response = $this->getJson(route('notes.show', $note));

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $note->id,
                    'description' => $note->description
                ]
            ]);
    }

    /** @test */
    public function it_updates_a_note()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ]);

        $data = [
            'description' => 'Updated Note',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ];

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->putJson(route('notes.update', $note), $data);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Producto creado correctamente']);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'description' => 'Updated Note'
        ]);
    }

    /** @test */
    public function it_does_not_allow_updating_another_users_note()
    {
        $otherUser = User::factory()->create();
        $note = Note::factory()->create([
            'user_id' => $otherUser->id,
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ]);

        $data = [
            'description' => 'Unauthorized Update',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ];

        $response = $this->withHeaders([
            'X-Session-ID' => $this->sessionId
        ])->putJson(route('notes.update', $note), $data);

        $response->assertStatus(401)
            ->assertJson(['message' => 'No puedes editar este registro']);
    }

    /** @test */
    public function it_deletes_a_note()
    {
        $note = Note::factory()->create([
            'user_id' => $this->user->id,
            'type_id' => $this->type->id,
            'group_id' => $this->group->id,
        ]);

        $response = $this->deleteJson(route('notes.destroy', $note));

        $response->assertStatus(200)
            ->assertJson(['message' => 'Registro eliminado correctamente']);

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }
}
