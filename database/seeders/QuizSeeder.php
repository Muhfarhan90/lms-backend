<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =====================================================
        // QUIZ 1 — Single choice + Essay (Lesson 1)
        // =====================================================
        $quiz1 = Quiz::updateOrCreate(
            ['lesson_id' => 1],
            [
                'title'            => 'Quiz: Dasar Pemrograman',
                'description'      => 'Uji pemahaman kamu tentang konsep dasar pemrograman.',
                'duration_minutes' => 30,
                'passing_score'    => 70,
                'weight'           => 40.00, // 40% dari nilai akhir course
                'is_active'        => true,
            ]
        );

        // Soal 1 — Single choice
        $q1 = Question::updateOrCreate(
            ['quiz_id' => $quiz1->id, 'sort_order' => 1],
            [
                'question_text' => 'Apa kepanjangan dari CPU?',
                'type'          => 'single_choice',
                'score'         => 20,
            ]
        );
        $this->createOptions($q1->id, [
            ['option_text' => 'Central Processing Unit',  'is_correct' => true],
            ['option_text' => 'Central Program Utility',  'is_correct' => false],
            ['option_text' => 'Computer Personal Unit',   'is_correct' => false],
            ['option_text' => 'Core Processing Utility',  'is_correct' => false],
        ]);

        // Soal 2 — Single choice
        $q2 = Question::updateOrCreate(
            ['quiz_id' => $quiz1->id, 'sort_order' => 2],
            [
                'question_text' => 'Manakah tipe data yang digunakan untuk menyimpan bilangan desimal?',
                'type'          => 'single_choice',
                'score'         => 20,
            ]
        );
        $this->createOptions($q2->id, [
            ['option_text' => 'Integer', 'is_correct' => false],
            ['option_text' => 'Boolean', 'is_correct' => false],
            ['option_text' => 'Float',   'is_correct' => true],
            ['option_text' => 'String',  'is_correct' => false],
        ]);

        // Soal 3 — Multiple choice
        $q3 = Question::updateOrCreate(
            ['quiz_id' => $quiz1->id, 'sort_order' => 3],
            [
                'question_text' => 'Pilih bahasa pemrograman yang termasuk OOP (boleh lebih dari satu):',
                'type'          => 'multiple_choice',
                'score'         => 20,
            ]
        );
        $this->createOptions($q3->id, [
            ['option_text' => 'Java',       'is_correct' => true],
            ['option_text' => 'Python',     'is_correct' => true],
            ['option_text' => 'HTML',       'is_correct' => false],
            ['option_text' => 'CSS',        'is_correct' => false],
            ['option_text' => 'PHP',        'is_correct' => true],
        ]);

        // Soal 4 — Short answer (auto-graded tidak tersedia, manual)
        Question::updateOrCreate(
            ['quiz_id' => $quiz1->id, 'sort_order' => 4],
            [
                'question_text' => 'Sebutkan perbedaan antara compiler dan interpreter!',
                'type'          => 'short_answer',
                'score'         => 20,
            ]
        );

        // Soal 5 — Essay (manual grading dari instructor)
        Question::updateOrCreate(
            ['quiz_id' => $quiz1->id, 'sort_order' => 5],
            [
                'question_text' => 'Jelaskan apa yang dimaksud dengan algoritma dan berikan contohnya dalam kehidupan sehari-hari!',
                'type'          => 'essay',
                'score'         => 20,
            ]
        );

        // =====================================================
        // QUIZ 2 — Murni pilihan ganda (Lesson 3)
        // =====================================================
        $quiz2 = Quiz::updateOrCreate(
            ['lesson_id' => 3],
            [
                'title'            => 'Quiz: Variabel dan Tipe Data',
                'description'      => 'Tes pemahaman kamu tentang variabel dan tipe data.',
                'duration_minutes' => 15,
                'passing_score'    => 75,
                'weight'           => 60.00, // 60% dari nilai akhir course
                'is_active'        => true,
            ]
        );

        // Soal 1
        $q5 = Question::updateOrCreate(
            ['quiz_id' => $quiz2->id, 'sort_order' => 1],
            [
                'question_text' => 'Pernyataan manakah yang benar tentang variabel?',
                'type'          => 'single_choice',
                'score'         => 25,
            ]
        );
        $this->createOptions($q5->id, [
            ['option_text' => 'Variabel adalah tempat menyimpan nilai sementara', 'is_correct' => true],
            ['option_text' => 'Variabel tidak dapat diubah nilainya',              'is_correct' => false],
            ['option_text' => 'Variabel hanya bisa menyimpan bilangan',            'is_correct' => false],
            ['option_text' => 'Variabel sama dengan konstanta',                    'is_correct' => false],
        ]);

        // Soal 2
        $q6 = Question::updateOrCreate(
            ['quiz_id' => $quiz2->id, 'sort_order' => 2],
            [
                'question_text' => 'Tipe data mana yang nilainya hanya true atau false?',
                'type'          => 'single_choice',
                'score'         => 25,
            ]
        );
        $this->createOptions($q6->id, [
            ['option_text' => 'Integer', 'is_correct' => false],
            ['option_text' => 'String',  'is_correct' => false],
            ['option_text' => 'Boolean', 'is_correct' => true],
            ['option_text' => 'Double',  'is_correct' => false],
        ]);

        // Soal 3
        $q7 = Question::updateOrCreate(
            ['quiz_id' => $quiz2->id, 'sort_order' => 3],
            [
                'question_text' => 'Pilih contoh penamaan variabel yang valid (boleh lebih dari satu):',
                'type'          => 'multiple_choice',
                'score'         => 25,
            ]
        );
        $this->createOptions($q7->id, [
            ['option_text' => '$namaSiswa',  'is_correct' => true],
            ['option_text' => '1variable',   'is_correct' => false],
            ['option_text' => '_totalHarga', 'is_correct' => true],
            ['option_text' => 'nama-siswa',  'is_correct' => false],
            ['option_text' => 'nilaiUjian',  'is_correct' => true],
        ]);

        // Soal 4 — Short answer
        Question::updateOrCreate(
            ['quiz_id' => $quiz2->id, 'sort_order' => 4],
            [
                'question_text' => 'Apa perbedaan antara tipe data int dan float? Berikan contoh masing-masing.',
                'type'          => 'short_answer',
                'score'         => 25,
            ]
        );
    }

    /**
     * Helper: buat options untuk satu question
     *
     * @param  int    $questionId
     * @param  array  $options  [ ['option_text' => ..., 'is_correct' => ...], ... ]
     */
    private function createOptions(int $questionId, array $options): void
    {
        foreach ($options as $option) {
            Option::updateOrCreate(
                [
                    'question_id' => $questionId,
                    'option_text' => $option['option_text'],
                ],
                ['is_correct' => $option['is_correct']]
            );
        }
    }
}
