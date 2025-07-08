<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        $questions = [
            'Bagaimana kondisi jalan utama di desa Anda?',
            'Apakah tersedia air bersih secara cukup?',
            'Bagaimana kualitas penerangan jalan?',
            'Apakah jaringan internet tersedia dengan baik?',
            'Bagaimana akses ke fasilitas kesehatan?',
        ];

        foreach ($questions as $q) {
            Question::create(['pertanyaan' => $q]);
        }
    }
}
