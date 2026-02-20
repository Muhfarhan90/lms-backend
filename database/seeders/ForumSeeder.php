<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Forum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        foreach ($courses as $course) {
            Forum::create([
                'course_id' => $course->id,
                'name' => $course->name,
            ]);
        }
    }
}
