<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function addAnswer(Request $request)
    {

        $text = $request->text;
        $question_id = $request->question_id;

        if ($text == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter text or image in Body',
                'status'=> 401
            ];
            return response($response , 401);

        }


        if ($question_id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter question_id in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }


        $question = Question::find($question_id);

        if ($question){

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

            $answer = Answer::create([
                'text' => $text,
                'image' => $image,
                'isCorrect'=> true,
                'question_id'=> $question_id,
            ]);


            if ($answer) {

                $response = [
                    'data'=>$answer,
                    'message'=> 'Successfully Added',
                    'status'=> 200
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

    public function getAnswers($id)
    {

        $question = Question::find($id);

        if ($question) {

            $answers = $question->answers;

            if (count($answers) > 0){

                $response = [
                    'data' => $answers,
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
                'message' => 'There is no question with this id',
                'status' => 401
            ];

            return response($response, 401);
        }

    }

    public function updateAnswer(Request $request)
    {

        $id = $request->id;
        $text = $request->text;


        if ($id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter id of answer in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }

        if ($text == null ){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter text in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }


        $answer = Answer::find($id);

        if (!$answer){

            $response = [
                'data'=>null,
                'message'=> 'Not valid answer id',
                'status'=> 401
            ];
            return response($response , 401);
        }

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
            $image =$answer->image;
        }


        $answer->update([
            'text' => $text ?: $answer->text,
            'image' => $image,
        ]);

        $response = [
            'data'=>$answer,
            'message'=> 'Successfully Updated',
            'status'=> 401
        ];
        return response($response , 401);



    }

    public function deleteAnswer()
    {

        $id = request('id');

        if ($id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter id query',
                'status'=> 401
            ];
            return response($response , 401);
        }

        $answer = Answer::find($id);

        if ($answer){

            $file = null;

            if ($answer->image){
                $file ='uploads/AnswersImages/'.$answer->image;
            }

            $answer->delete();

            if (file_exists($file)){
                unlink($file);
            }


            $response = [
                'data'=>null,
                'message'=> 'Deleted Successfully',
                'status'=> 200
            ];

            return response($response , 200);

        } else {

            $response = [
                'data'=>null,
                'message'=> 'Not valid id',
                'status'=> 401
            ];

            return response($response , 401);

        }

    }





    public function addAnswerView(){
        $question_id = request('question_id');
        return view('add_answer' , ['question_id' =>$question_id]);
    }

    public function addAnswerFun(Request $request)
    {

        $text = $request->text;
        $question_id = $request->question_id;

        $imageName = null;

        if ($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $imageName = time() . ".". $extension;
            $file->move('uploads/AnswersImages/', $imageName);
        }

        $image = $imageName;

        $question = Question::find($question_id);

        Answer::create([
            'text' => $text,
            'image' => $image,
            'isCorrect' => false,
            'question_id' => $question_id
        ]);

        // return redirect('http://138.68.74.161/question?category_id='.$question->category_id);
        return redirect('/question?category_id='.$question->category_id);


    }

    public function updateAnswerView(){
        $answer_id = request('answer_id');
        $answer = Answer::find($answer_id);
        return view('update_answer' , ['answer'=>$answer]);
    }

    public function updateAnswerFun(Request $request)
    {

        $answer_id = $request->answer_id;
        $text = $request->text;


        $answer = Answer::find($answer_id);

        $question = Question::find($answer->question_id);

        $imageName = null;

        if ($answer_id == null || $text == null){

            return redirect()->route('question',['category_id'=> $question->category_id]);

        }

        if ($answer) {


            if ($request->hasFile('image')){

                //Delete Previous Img
                if ($answer->image){
                    $file ='uploads/AnswersImages/'.$answer->image;
                    if (file_exists($file)){
                        unlink($file);
                    }
                }


                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . ".". $extension;
                $file->move('uploads/AnswersImages/', $imageName);

            }


            $image = $imageName;

            $answer->update([
                'text' => $text ?: $answer->text,
                'image' => $image ?: $answer->image
            ]);

        }
        return redirect()->route('question',['category_id'=> $question->category_id]);
    }

    public function deleteAnswerView()
    {
        $answer_id = request('answer_id');

        $answer = Answer::find($answer_id);


        $question = Question::find($answer->question_id);

        if ($answer) {

            $file = null;

            if ($answer->image){
                $file ='uploads/AnswersImages/'.$answer->image;
                return $file;
            }

            $answer->delete();

            if (file_exists($file)){
                unlink($file);
            }
        }

        return redirect()->route('question',['category_id'=> $question->category_id]);

    }

}
