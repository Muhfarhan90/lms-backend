<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'course_id' => 1,
                'title' => 'Introduction to Programming',
                'sort_order' => 1,
            ],
            [
                'course_id' => 1,
                'title' => 'Variables and Data Types',
                'sort_order' => 2,
            ],
            [
                'course_id' => 2,
                'title' => 'Getting Started with Web Development',
                'sort_order' => 1,
            ],
            [
                'course_id' => 2,
                'title' => 'HTML Basics',
                'sort_order' => 2,
            ],
        ];
        foreach ($sections as $section) {
            Section::updateOrCreate($section);
        }
    }
}
