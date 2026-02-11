<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportQuestionController extends Controller
{
    //
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'category_id' => 'required|integer',
        ]);

        // Get the category_id from the request
        $category_id = $request->input('category_id');

        // return response()->json([
        //     "data"=>$category_id
        // ]);
        // Load the CSV file
        $file = $request->file('file');
        $csvData = array_map('str_getcsv', file($file->getRealPath()));

        // Extract the header
        $header = $csvData[0];
        unset($csvData[0]);

        foreach ($csvData as $row) {
            $row = array_combine($header, $row);

            // Create the question
            $question = Question::create([
                'title' => $row['questions'], // Assuming 'questions' is the column in the CSV for the question text
                'answer' => $row['correct_answers'],
                'category_id' => $category_id, // Assign the category_id from the URL
            ]);

            // Explode the options (assuming they are separated by slashes "/")
            $options = explode('/', $row['options']);

            // Define the correct answer
            $correctAnswer = $row['correct_answers'];

            foreach ($options as $option) {
                // Create an answer for each option
                Answer::create([
                    'text' => $option,
                    'isCorrect' => ($option == $correctAnswer) ? 1 : 0, // Mark correct answer
                    'question_id' => $question->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Questions Imported Successfully');
    }
}
