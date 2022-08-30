<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Questions;

class QuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = 100;

        for($i = 0; $i < $questions; $i++) {
            $points = rand(1, 5);
            $insert = new Questions();
            $insert->question = "Test$i?";
            $insert->points = $points;
            $insert->save();
        }
    }
}
