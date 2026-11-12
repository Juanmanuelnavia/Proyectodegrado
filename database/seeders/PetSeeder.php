<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $pets = [
            ['owner_id' => 1, 'name' => 'Max', 'species' => 'perro', 'breed' => 'Labrador', 'birth_date' => '2020-03-15', 'gender' => 'macho', 'color' => 'Dorado', 'weight' => 28.5],
            ['owner_id' => 1, 'name' => 'Luna', 'species' => 'gato', 'breed' => 'Persa', 'birth_date' => '2021-07-20', 'gender' => 'hembra', 'color' => 'Blanco', 'weight' => 4.2],
            ['owner_id' => 2, 'name' => 'Rocky', 'species' => 'perro', 'breed' => 'Bulldog Francés', 'birth_date' => '2019-11-10', 'gender' => 'macho', 'color' => 'Atigrado', 'weight' => 12.0],
            ['owner_id' => 3, 'name' => 'Mia', 'species' => 'gato', 'breed' => 'Siamés', 'birth_date' => '2022-01-05', 'gender' => 'hembra', 'color' => 'Crema con puntas', 'weight' => 3.8],
            ['owner_id' => 4, 'name' => 'Thor', 'species' => 'perro', 'breed' => 'Golden Retriever', 'birth_date' => '2018-06-25', 'gender' => 'macho', 'color' => 'Dorado', 'weight' => 32.0],
            ['owner_id' => 5, 'name' => 'Coco', 'species' => 'perro', 'breed' => 'Poodle', 'birth_date' => '2021-09-14', 'gender' => 'hembra', 'color' => 'Blanco', 'weight' => 6.5],
            ['owner_id' => 6, 'name' => 'Simba', 'species' => 'gato', 'breed' => 'Común europeo', 'birth_date' => '2020-12-01', 'gender' => 'macho', 'color' => 'Naranja', 'weight' => 5.1],
            ['owner_id' => 7, 'name' => 'Bella', 'species' => 'perro', 'breed' => 'Beagle', 'birth_date' => '2019-04-18', 'gender' => 'hembra', 'color' => 'Tricolor', 'weight' => 10.3],
        ];

        foreach ($pets as $pet) {
            Pet::create($pet);
        }
    }
}