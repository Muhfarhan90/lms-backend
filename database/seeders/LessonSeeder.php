<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = [
            [
                'section_id' => 1,
                'title' => 'What is Programming?',
                'type' => 'video',
                'content_url' => 'https://example.com/lesson1.mp4',
                'sort_order' => 1,
            ],
            [
                'section_id' => 1,
                'title' => 'Setting Up Your Environment',
                'type' => 'article',
                'content_url' => 'https://example.com/lesson2.html',
                'sort_order' => 2,
            ],
            [
                'section_id' => 2,
                'title' => 'Understanding Variables',
                'type' => 'video',
                'content_url' => 'https://example.com/lesson3.mp4',
                'sort_order' => 1,
            ],
            [
                'section_id' => 2,
                'title' => 'Data Types Overview',
                'type' => 'article',
                'content_url' => 'https://example.com/lesson4.html',
                'sort_order' => 2,
            ],
        ];
        foreach ($lessons as $lesson) {
            Lesson::updateOrCreate($lesson);
        }
    }
}
