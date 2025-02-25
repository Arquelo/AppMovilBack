<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["type" => "Otro"],
            ["type" => "Grupal"],
            ["type" => "Individual"],
            ["type" => "Familiar"],
            ["type" => "Pareja"],
            ["type" => "Amigos"],
            ["type" => "Niños"],
            ["type" => "Adultos"],
            ["type" => "Jovenes"],
            ["type" => "Mayores"],
            ["type" => "Hombres"],
            ["type" => "Mujeres"],
            ["type" => "Mixto"],
            ["type" => "Masculino"],
            ["type" => "Femenino"],
            ["type" => "Unisex"],
            ["type" => "Mascotas"],
            ["type" => "Mujeres"],
            ["type" => "Hombres"],
            ["type" => "Mixto"],
            ["type" => "Masculino"],
            ["type" => "Femenino"],
            ["type" => "Unisex"],
            ["type" => "Mascotas"],
            ["type" => "Mujeres"],
            ["type" => "Hombres"],
            ["type" => "Mixto"],
            ["type" => "Masculino"],
            ["type" => "Femenino"],
            ["type" => "Unisex"],
            ["type" => "Mascotas"],
            ["type" => "Mujeres"],
            ["type" => "Hombres"],
            ["type" => "Mixto"],
            ["type" => "Masculino"],
            ["type" => "Femenino"],
            ["type" => "Unisex"],
            ["type" => "Mascotas"],
            ["type" => "Mujeres"],
            ["type" => "Hombres"],
            ["type" => "Mixto"],
            ["type" => "Masculino"],
            ["type" => "Femenino"],
            ["type" => "Unisex"],
            ["type" => "Mascotas"],
            ["type" => "Mujeres"],
            ["type" => "Hombres"],
            ["type" => "Mixto"],
            ["type" => "Masculino"],
            ["type" => "Femenino"],
            ["type" => "Unisex"],
            ["type" => "Mascotas"],
            ["type" => "Mujeres"],
            ["type" => "Hombres"],
            ["type" => "Mixto"],
        ];

        foreach ($data as $type) {
            Type::create($type);
        }
    }
}
