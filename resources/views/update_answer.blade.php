@extends('layouts.app')

@section('title')
    Update Answer
@endsection



@section('content')


    <form method="post" action="{{route('updateAnswerFun' , ['answer_id'=>$answer->id] , $absolute = false)}}" enctype="multipart/form-data">
        @csrf

        <div class="form-group mt-4" style="margin-left: 5%; width: 90%">
            <label for="gg">Answer Text</label>
            <input type="text"  value="{{$answer->text}}" class="form-control " name="text" required  placeholder="Title">
        </div>

        <br>

        <div class="form-group" style="margin-left: 5%; width: 90%">
            <label >Answer Image</label>
            <input type="file" class="form-control " name="image"  placeholder="Answer">

        </div>

        <br>
        <br>
        @if($answer->image)
            <img src="{{ asset("uploads/AnswersImages/".$answer->image) }}"  style="margin-left: 39%; width : 400px; height : 400px" alt="Image"  >
        @endif

        <br>
        <br>


        <div class="text-center">

            <input type="submit" class="btn btn-primary ms-3 me-3" style="width: 90%" value="Submit">

            <br>
            <br>

        </div>

    </form>

    <div class="text-center">

    <form class="form-group" action="{{route('deleteAnswer' , ['answer_id'=>$answer->id])}}" method="post">
        @csrf
        <input style="width: 90%" type="submit" value="Delete Answer" class="btn btn-danger">

    </form>

    </div>


@endsection
