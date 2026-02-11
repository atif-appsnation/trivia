@extends('layouts.app')

@section('title')
    Edit Question
@endsection

@section('content')

    <form action="{{route('updateQuestionFun' , ['category_id'=>$category_id ,'question_id'=>$question_id , 'title'=>$title , 'answer'=>$answer] , $absolute = false)}}" method="post">
        @csrf
        <br>

        <div class="form-group ms-3 me-3">
            <label style="font-size: 18px" for="nameTextField">Question</label>
            <br>
            <input style="font-size: 17px" type="text" value="{{$title}}" class="form-control " name="title" required id="nameTextField" placeholder="Title">
        </div>

        <div class="form-group ms-3 me-3">
            <label style="font-size: 18px" for="answerTextField">Correct Answer</label>
            <br>
            <input style="font-size: 17px" type="text" value="{{$answer}}" class="form-control " name="answer" required id="answerTextField" placeholder="Answer">
        </div>

        <br>
        <button style="font-size: 15px" type="submit" class="btn btn-primary ms-3 me-3">Submit</button>

    </form>



@endsection
