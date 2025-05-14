<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaboratoryEquipment;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;

class LaboratoryEquipmentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Predefined items
        $equipments = [
            [
                'category_id' => 5,
                'equipment' => 'Oscilloscope',
                'description' => 'Used for measuring electrical signals.',
                'brand' => 'Tektronix',
                'quantity' => 5,
                'date_acquired' => Carbon::now()->subYears(2),
                'unit' => 'pcs'
            ],
            [
                'category_id' => 5,
                'equipment' => 'Digital Multimeter',
                'description' => 'Used to measure voltage, current, and resistance.',
                'brand' => 'Fluke',
                'quantity' => 10,
                'date_acquired' => Carbon::now()->subYears(3),
                'unit' => 'pcs'
            ],
            [
                'category_id' => 5,
                'equipment' => 'Soldering Station',
                'description' => 'Used for soldering electronic components.',
                'brand' => 'Hakko',
                'quantity' => 8,
                'date_acquired' => Carbon::now()->subYears(1),
                'unit' => 'set'
            ],
            [
                'category_id' => 5,
                'equipment' => 'Function Generator',
                'description' => 'Generates electrical waveforms for circuit testing.',
                'brand' => 'Rigol',
                'quantity' => 5,
                'date_acquired' => Carbon::now()->subYears(4),
                'unit' => 'pcs'
            ],
        ];

        // Generate 20 fake equipment items
        for ($i = 0; $i < 20; $i++) {
            $equipments[] = [
                'category_id' => 5,
                'equipment' => $faker->word . ' ' . $faker->randomElement(['Tester', 'Analyzer', 'Monitor', 'Sensor']),
                'description' => $faker->sentence(6),
                'brand' => $faker->company,
                'quantity' => $faker->numberBetween(1, 20),
                'date_acquired' => $faker->dateTimeBetween('-5 years', 'now'),
                'unit' => $faker->randomElement(['pcs', 'set', 'unit'])
            ];
        }

        foreach ($equipments as $equipment) {
            LaboratoryEquipment::updateOrCreate(
                ['equipment' => $equipment['equipment']],
                $equipment
            );
        }
    }
}
