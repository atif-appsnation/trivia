<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use function PHPUnit\Framework\isEmpty;
use function Sodium\add;

class QuestionController extends Controller
{
    public function addQuestion(Request $request)
    {

        $title = $request->title;
        $answer = $request->answer;
        $category_id = $request->category_id;


        if ($title == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter title or image in Body',
                'status'=> 401
            ];
            return response($response , 401);

        }


        if ($answer == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter answer in Body',
                'status'=> 401
            ];
            return response($response , 401);

        }

        if ($category_id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter category_id in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }



        $cat = Category::find($category_id);

        if ($cat){

            $question = Question::create([
                'title' => $title,
                'answer' => $answer,
                'category_id'=> $category_id,
            ]);

            $imageName = null;

            if ($request->hasFile('image')){
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . ".". $extension;
                $file->move('uploads/AnswersImages/', $imageName);
            }

            if ($imageName){
                $image = asset("uploads/AnswersImages/".$imageName);
            } else {
                $image =null;
            }


            Answer::create([
                'text' => $answer,
                'image' => $image,
                'isCorrect'=> true,
                'question_id'=> $question->id,
            ]);


            if ($question) {

                $response = [
                    'data' => $question,
                    'message' => 'Added Successfully',
                    'status' => 200
                ];

                return response($response, 200);

            } else {

                $response = [
                    'data' => null,
                    'message' => 'Something went wrong',
                    'status' => 401
                ];

                return response($response, 401);

            }

        } else {

            $response = [
                'data' => null,
                'message' => 'There\'s no category with this id ',
                'status' => 401
            ];

            return response($response, 401);

        }

    }

    public function getQuestionsOfCategory($id)
    {
        $cat = Category::find($id);

        if ($cat) {

            $questions = $cat->questions;

            if (count($questions) > 0){

                $response = [
                    'data' => $questions,
                    'message' => 'Fetched Data Successfully',
                    'status' => 200
                ];

                return response($response, 200);

            } else {

                $response = [
                    'data' => null,
                    'message' => 'No questions found for this category',
                    'status' => 401
                ];

                return response($response, 401);

            }


        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);
        }

    }

    public function updateQuestion(Request $request)
    {

        $title = null;
        $answer = null;


        if ($request->title){
            $title = $request->title;
        }

        if ($request->answer){
            $answer = $request->answer;
        }

        $id = $request->id;

        if ($id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter id in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }


        $question = Question::find($id);

        if ($question) {

            $question->update([
                'title' => $title ?: $question->title,
                'answer' => $answer ?: $question->answer,
            ]);

            $response = [
                'data' => $question,
                'message' => 'Updated Successfully',
                'status' => 200
            ];

            return response($response, 200);

        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);

        }


    }

    public function deleteQuestion()
    {

        $question_id = request('id');

        if ($question_id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter id in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }

        $question = Question::find($question_id);

        if ($question) {

            foreach ($question->answers as $answer) {

                $file = null;

                if ($answer->image) {
                    $file = 'uploads/AnswersImages/' . $answer->image;
                }

                $answer->delete();

                if (file_exists($file)) {
                    unlink($file);
                }
            }

            $question->delete();

            $response = [
                'data'=>null,
                'message'=> 'Deleted Successfully',
                'status'=> 200
            ];

            return response($response , 200);

        } else {
            $response = [
                'data'=>null,
                'message'=> 'There\'s no question with this id',
                'status'=> 401
            ];
            return response($response , 401);
        }

    }


    public function questionPage(){
        $cat = Category::find(request('category_id'));
        $listOfAnswers = array();
        if ($cat) {
            $questions = $cat->questions;

            foreach ($questions as $question){
                $answers = $question->answers;
                $listOfAnswers[$question->id] = $answers;
            }
        }
        return view('question' , ['questions'=>$questions ,'answers'=>$listOfAnswers ,  'category_id' =>$cat->id]);
    }

    public function addQuestionView(){
        $category_id = request('category_id');
        return view('add_question' , ['category_id' =>$category_id]);
    }

    public function addQuestionFun(Request $request)
    {

        $title = $request->title;
        $answer = $request->answer;
        $category_id = $request->category_id;

        $cat = Category::find($category_id);

        if ($cat) {

            $question = Question::create([
                'title' => $title,
                'answer' => $answer,
                'category_id' => $category_id,
            ]);

            $imageName = null;

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . "." . $extension;
                $file->move('uploads/AnswersImages/', $imageName);
            }

            if ($imageName) {
                $image = asset("uploads/AnswersImages/" . $imageName);
            } else {
                $image = null;
            }


            Answer::create([
                'text' => $answer,
                'image' => $image,
                'isCorrect' => true,
                'question_id' => $question->id,
            ]);

            return redirect()->route('question', ['category_id' => $category_id]);
        }
    }

    public function updateQuestionView(){
        $category_id = request('category_id');
        $question_id = request('question_id');
        $title = request('title');
        $answer = request('answer');
        return view('update_question' , ['question_id'=>$question_id ,'category_id'=>$category_id , 'title'=>$title , 'answer'=>$answer]);
    }

    public function updateQuestionFun()
    {

        $category_id = request('category_id');
        $question_id = request('question_id');
        $title = request('title');
        $answer = request('answer');


        if ($category_id == null || $title == null){

            return redirect()->route('question',['category_id'=> $category_id]);
        }


        $question = Question::find($question_id);

        if ($question) {

            $question->update([
                'title' => $title,
                'answer' => $answer
            ]);

            return redirect()->route('question',['category_id'=> $category_id]);

        } else {

            return redirect()->route('question',['category_id'=> $category_id]);

        }
    }

    public function deleteQuestionView()
    {

        $question_id = request('question_id');
        $category_id = request('category_id');

        $question = Question::find($question_id);

        if ($question) {

            foreach ($question->answers as $answer) {

                $file = null;

                if ($answer->image) {
                    $file = 'uploads/AnswersImages/' . $answer->image;
                }

                $answer->delete();

                if (file_exists($file)) {
                    unlink($file);
                }
            }

            $question->delete();

        }

        return redirect()->route('question',['category_id'=> $category_id]);

    }
}
