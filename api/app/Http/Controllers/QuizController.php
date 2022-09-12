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
use App\Models\Quiz;
use App\Models\QuizUsers;
use App\Models\Rewarded;

class QuizController extends Controller
{
    public function generateQuiz(Request $request) {
        try {
            $this->validate($request,[
                'user_id' => "required|integer"
            ]);
            $quiz = Quiz::where('active', '=', true)->first();
            if($quiz) {
                // if($quiz->random) {
                //     if(!$request->has('quiz_id')) {
                //         $question_ids = [];
        
                //         while(count($question_ids) < 5) {
                //             $id = rand(1, 100);
                //             array_push($question_ids, $id);
                //             $question_ids = array_unique($question_ids);
                //         }
        
                //         $questions = implode(",", $question_ids);
        
                //         $start = new StartedQuizes();
                //         $start->started_by = $request->user_id;
                //         $start->finished = false;
                //         $start->questions = $questions;
                //         $start->save();
        
                //         $data = Questions::whereIn('id', $question_ids)->get();
        
                //         return response()->json(['success' => true, 'questions' => $data, 'quiz_id' => $start], 200);
                //     }
                //     $question_ids = StartedQuizes::where('id', '=', $request->quiz_id)->first();

                //     $question_ids = explode(",", $question_ids->questions);

                //     $data = Questions::whereIn('id', $question_ids)->get();


                //     return response()->json(['success' => true, 'questions' => $data], 200);
                // } 
                $questions = Questions::where('quiz_id', '=', $quiz->id)->get()->toArray();
                $question_ids = [];
                foreach($questions as $question) {
                    array_push($question_ids, $question['id']);
                }

                $question_ids = implode(",", $question_ids);
                $start = new StartedQuizes();
                $start->quiz_id = $quiz->id;
                $start->started_by = $request->user_id;
                $start->finished = false;
                $start->questions = $question_ids;
                $start->save();

                return response()->json(['success' => true, 'questions' => $questions, 'quiz_id' => $start, 'quiz_data' => $quiz], 200);
            }
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

    public function finishQuiz(Request $request) {
        try {
            $this->validate($request,
                [
                    'quiz_id' => "required|integer",
                    'user_id' => "required|integer"
                ]
            );
            StartedQuizes::where('id', '=', $request->quiz_id)->update(['finished' => true]);
            $data = StartedQuizes::where('id', '=', $request->quiz_id)->first();
            return response()->json(['success' => true, 'data' => $data], 200);
        } catch (Exception $e) {
            Log::error("Error finishing quiz", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }

    public function calculateResult(Request $request) {
        try {
            $this->validate($request, [
                'started_quiz_id' => "required|integer",
                'quiz_id' => "required|integer"
            ]);

            $result = $this->calcResult($request->started_quiz_id);
            
            if($result['success']) {
                return response()->json(
                    [
                        'success' => true, 
                        'points' => $result['points'], 
                        'total' => $result['total'], 
                        'percent' => $result['percent'], 
                        'passing' => $result['passing']
                    ], 200);
            }
            return response()->json(['success' => false, 'error' => $result['error']], 200);
        } catch (Exception $e) {
            Log::error("Error calculating win percent", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }

    private function calcResult(int $quiz_id):array {
        try {
            $data = SubmittedAnswers::join('quiz_answers', 'submitted_answers.answer_id', '=', 'quiz_answers.id')
            ->leftjoin('questions', 'submitted_answers.question_id', '=', 'questions.id')
            ->where('submitted_answers.quiz_id', '=', $quiz_id)
            //->where('quiz_answers.correct', '=', 'true')
            ->get()
            ->toArray();
        
            $passing = Quiz::join('started_quizes', 'started_quizes.quiz_id', '=', 'quizzes.id')
                ->where('started_quizes.id', '=', $quiz_id)
                ->first();
            if($passing) {
                $passing = $passing->passing_grade;
            }

            $points = 0;
            $total = 0;
            if(!empty($data)) {
                foreach($data as $row) {
                    if($row['correct']) {
                        $points = $points + (int)$row['points'];
                    }
                    $total = $total + (int)$row['points'];
                }
            }

            $percent = round(($points / $total) * 100);
            return ['success' => true, 'points' => $points, 'total' =>  $total, 'percent' => $percent, 'passing' => (int)$passing];
        } catch (Exception $e) {
            Log::error("Error calculating result (private function)", ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function userResult(Request $request) {
        try {
            $this->validate($request, [
                'userid' => "required|integer"
            ]);
            
            $quiz_id = StartedQuizes::where('started_by', '=', $request->userid)->where('finished', '=', true)->orderBy('id', 'desc')->limit(1)->first();
            //return response()->json(['success' => true, 'test' => $quiz_id], 200);

            $result = $this->calcResult($quiz_id->id);

            $user = QuizUsers::where('id', '=', $request->userid)->first();
            $reward = new Rewarded();
            $rewarded = $reward->checkRewardState($request->userid);
            if($rewarded['success']) {
                $rewarded = $rewarded['data'];
            }

            return response()->json(['success' => true, 'result' => $result, 'user' => $user, 'reward_state' => $rewarded, 'quiz_id' => $quiz_id->id], 200);
        } catch (Exception $e) {
            Log::error("Error fetching user result", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }

    public function rewardUser(Request $request) {
        try {
            $this->validate($request, [
                'user_id' => "required|integer",
                'quiz_id' => "required|integer"
            ]);

            $reward = new Rewarded();
            $rewarded = $reward->insert($request->user_id, $request->quiz_id);

            return response()->json(['success' => true], 200);
        } catch (Exception $e) {
            Log::error("Error rewarding user", ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 200);
        }
    }
}
