<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\QuizAnswers;
Use App\Models\Questions;

class AnswersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = Questions::get()->toArray();
        foreach($questions as $question) {
            $correctAnswers = rand(1, 4);
            $submitedCorrectAnswers = 0;
            for($i = 0; $i < 4; $i++) { 
                $answer = new QuizAnswers();
                $answer->question_id = $question['id'];
                $answer->answer = "Answer $i";
                if($submitedCorrectAnswers < $correctAnswers) {
                    $answer->correct = true;
                    $submitedCorrectAnswers++;
                } else {
                    $answer->correct = false;
                }

                $answer->save();
            }
        }
    }
}
