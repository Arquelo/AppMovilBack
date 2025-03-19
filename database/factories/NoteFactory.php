<?php

namespace Database\Factories;

use App\Models\Note;
use App\Models\Type;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    protected $model = Note::class;

    public function definition(): array
    {
        $startDate = $this->faker->date();
        $endDate = $this->faker->date('Y-m-d', strtotime($startDate . ' +5 days'));

        return [
            'description' => $this->faker->sentence(),
            'completed' => $this->faker->boolean(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'type_id' => Type::factory(),
            'group_id' => Group::factory(),
            'user_id' => User::factory(),
        ];
    }
}
