<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Konser Musik',
                'description' => 'Pertunjukan musik langsung dengan berbagai genre',
                'icon' => 'fas fa-music',
                'color' => '#FF6B6B',
            ],
            [
                'name' => 'Teater',
                'description' => 'Pertunjukan drama dan teater profesional',
                'icon' => 'fas fa-theater-masks',
                'color' => '#4ECDC4',
            ],
            [
                'name' => 'Festival',
                'description' => 'Festival budaya dan hiburan',
                'icon' => 'fas fa-flag',
                'color' => '#45B7D1',
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Event pertandingan dan olahraga',
                'icon' => 'fas fa-futbol',
                'color' => '#96CEB4',
            ],
            [
                'name' => 'Seminar',
                'description' => 'Acara edukatif dan workshop',
                'icon' => 'fas fa-graduation-cap',
                'color' => '#FFEAA7',
            ],
            [
                'name' => 'Stand Up Comedy',
                'description' => 'Pertunjukan komedi stand up',
                'icon' => 'fas fa-laugh',
                'color' => '#DDA15E',
            ],
            [
                'name' => 'Pameran',
                'description' => 'Pameran seni dan budaya',
                'icon' => 'fas fa-image',
                'color' => '#BC6C25',
            ],
            [
                'name' => 'Konferensi',
                'description' => 'Konferensi bisnis dan networking',
                'icon' => 'fas fa-users',
                'color' => '#2E86AB',
            ],
        ];

        foreach ($categories as $category) {
            EventCategory::create($category);
        }
    }
}
