<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'category_id' => 1, // Programming
                'instructor_id' => 2, // Dr. Elon Musk
                'title' => 'Introduction to Python',
                'description' => 'Learn the basics of Python programming language.',
                'price' => 49.99,
                'discount_price' => 29.99,
                'thumbnail' => 'python_course.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => 2, // Data Science
                'instructor_id' => 2, // Dr. Elon Musk
                'title' => 'Data Science with R',
                'description' => 'Master data science techniques using R programming language.',
                'price' => 59.99,
                'discount_price' => 39.99,
                'thumbnail' => 'data_science_course.jpg',
                'is_active' => true,
            ],
            [
                'category_id' => 3, // Design
                'instructor_id' => 2, // Dr. Elon Musk
                'title' => 'Graphic Design Fundamentals',
                'description' => 'Learn the principles of graphic design and create stunning visuals.',
                'price' => 39.99,
                'discount_price' => 19.99,
                'thumbnail' => 'graphic_design_course.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate($course);
        }
    }
}
