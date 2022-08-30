<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Questions;
use App\Models\QuizAnswers;
use App\Models\SubmittedAnswers;
use App\Models\StartedQuizes;

class QuizController extends Controller
{
    public function generateQuiz(Request $request) {
        try {
            $this->validate($request,[
                'user_id' => "required|integer"
            ]);
            $question_ids = [];

            while(count($question_ids) < 5) {
                $id = rand(1, 100);
                array_push($question_ids, $id);
                $question_ids = array_unique($question_ids);
            }

            $start = new StartedQuizes();
            $start->started_by = $request->user_id;
            $start->finished = false;
            $start->save();

            $data = Questions::whereIn('id', $question_ids)->get();

            return response()->json(['success' => true, 'questions' => $data, 'quiz_id' => $start], 200);
        } catch (Exception $e) {
            Log::error("Error generating quiz", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }

    public function fetchAnswer(Request $request) {
        try {
            $this->validate($request, [
                'question_id' => "required|integer"
            ]);

            $data = QuizAnswers::where('question_id', '=', $request->question_id)->get();
            return response()->json(['success' => true, 'answers' => $data], 200);
        } catch (Exception $e) {
            Log::error("Error fetching answers", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }

    public function submitAnswer(Request $request) {
        try {
            $this->validate($request, 
                [
                    'user_id' => "required|integer",
                    'question_id' => "required|integer",
                    'answers' => "required|string",
                    'quiz_id' => "required|integer"
                ]
            );

            $answers = json_decode($request->answers, true);

            foreach($answers as $answer) {
                $insert = new SubmittedAnswers();
                $insert->user_id = $request->user_id;
                $insert->quiz_id = $request->quiz_id;
                $insert->question_id = $request->question_id;
                $insert->answer_id = $answer['id'];
                $insert->save();
            }

            return response()->json(['success' => true, 'fields' => $request->all()], 200);
        } catch (Exception $e) {
            Log::error("Error saving answer", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }
}
