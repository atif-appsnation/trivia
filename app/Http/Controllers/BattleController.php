<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Battle;
use App\Models\Category;
use App\Models\Question;

class BattleController extends Controller
{
    //
    // public function startBattle(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'category_id' => 'required|exists:categories,id',
    //             'battle_id' => 'required|string'
    //         ]);

    //         $categoryId = $request->category_id;
    //         $battleId = $request->battle_id;

    //         // Check if this battle_id already exists
    //         $existingBattle = Battle::where('battle_id', $battleId)->first();

    //         if ($existingBattle) {
    //             $questionIds = json_decode($existingBattle->question_ids);
    //         } else {
    //             $questionIds = Question::where('category_id', $categoryId)
    //                 ->inRandomOrder()
    //                 ->take(24)
    //                 ->pluck('id')
    //                 ->toArray();

    //             // Save new battle with battle_id
    //             Battle::create([
    //                 'battle_id' => $battleId,
    //                 'category_id' => $categoryId,
    //                 'question_ids' => json_encode($questionIds),
    //             ]);
    //         }

    //         $questions = Question::with('answers')
    //             ->whereIn('id', $questionIds)
    //             ->get()
    //             ->shuffle()
    //             ->map(function ($question) {
    //                 return [
    //                     'id' => $question->id,

    //                     'question' => $question->title,
    //                     'answers' => $question->answers->map(function ($ans) {
    //                         return [
    //                             'text' => $ans->text,
    //                             'correct_answer' => $ans->isCorrect == 1
    //                         ];
    //                     }),
    //                 ];
    //             });

    //         return response()->json([
    //             'status' => 200,
    //             'message' => $existingBattle ? 'Battle joined' : 'Battle created',
    //             'battle_id' => $battleId,
    //             'data' => $questions,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'Something went wrong.',
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }

    //     public function startBattle(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'category_id' => 'required|exists:categories,id',
    //             'battle_id' => 'required|string'
    //         ]);

    //         $categoryId = $request->category_id;
    //         $battleId = $request->battle_id;

    //         $battle = Battle::where('battle_id', $battleId)->first();

    //         if (!$battle) {
    //             // Get questions first
    //             $questionIds = Question::where('category_id', $categoryId)
    //                 ->inRandomOrder()
    //                 ->take(24)
    //                 ->pluck('id')
    //                 ->toArray();

    //             try {
    //                 // Try to create the battle (atomic)
    //                 $battle = Battle::create([
    //                     'battle_id' => $battleId,
    //                     'category_id' => $categoryId,
    //                     'question_ids' => json_encode($questionIds),
    //                 ]);
    //             } catch (\Illuminate\Database\QueryException $e) {
    //                 // If duplicate key exception, someone else already created it
    //                 $battle = Battle::where('battle_id', $battleId)->first();
    //             }
    //         }

    //         $questionIds = json_decode($battle->question_ids);

    //         $questions = Question::with('answers')
    //             ->whereIn('id', $questionIds)
    //             ->get()
    //             ->shuffle()
    //             ->map(function ($question) {
    //                 return [
    //                     'id' => $question->id,
    //                     'question' => $question->title,
    //                     'answers' => $question->answers->map(function ($ans) {
    //                         return [
    //                             'text' => $ans->text,
    //                             'correct_answer' => $ans->isCorrect == 1
    //                         ];
    //                     }),
    //                 ];
    //             });

    //         return response()->json([
    //             'status' => 200,
    //             'message' => $battle->wasRecentlyCreated ?? false ? 'Battle created' : 'Battle joined',
    //             'battle_id' => $battleId,
    //             'data' => $questions,
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 500,
    //             'message' => 'Something went wrong.',
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }    
    public function startBattle(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'battle_id' => 'required|string'
            ]);

            $categoryId = $request->category_id;
            $battleId = $request->battle_id;

            // Check if the battle already exists
            $battle = Battle::where('battle_id', $battleId)->first();

            if (!$battle) {
                // Get random questions
                $questionIds = Question::where('category_id', $categoryId)
                    ->inRandomOrder()
                    ->take(24)
                    ->pluck('id')
                    ->toArray();

                // Attempt to create the battle with retry logic (handles race conditions)
                $attempts = 0;
                do {
                    try {
                        $battle = Battle::create([
                            'battle_id' => $battleId,
                            'category_id' => $categoryId,
                            'question_ids' => json_encode($questionIds),
                        ]);
                        break; // success
                    } catch (\Illuminate\Database\QueryException $e) {
                        // Likely duplicate battle_id due to race condition
                        usleep(100000); // wait 100ms before retrying
                        $battle = Battle::where('battle_id', $battleId)->first();
                        $attempts++;
                    }
                } while (!$battle && $attempts < 3);
            }

            // Decode the question IDs from the battle
            $questionIds = json_decode($battle->question_ids);

            // Fetch questions and their answers
            $questions = Question::with('answers')
                ->whereIn('id', $questionIds)
                ->get()
                ->shuffle()
                ->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question' => $question->title,
                        'answers' => $question->answers->map(function ($ans) {
                            return [
                                'text' => $ans->text,
                                'correct_answer' => $ans->isCorrect == 1
                            ];
                        }),
                    ];
                });

            return response()->json([
                'status' => 200,
                'message' => $battle->wasRecentlyCreated ?? false ? 'Battle created' : 'Battle joined',
                'battle_id' => $battleId,
                'data' => $questions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
