<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Programming',
                'description' => 'Courses related to programming languages and software development.',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Courses on data analysis, machine learning, and statistics.',
            ],
            [
                'name' => 'Design',
                'description' => 'Courses covering graphic design, UI/UX, and multimedia.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate($category);
        }
    }
}
