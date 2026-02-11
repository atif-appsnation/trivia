@extends('layouts.app')

@section('title')
    Add Answer
@endsection

@section('content')



    <form action="{{route('addAnswerFun' ,['question_id'=>$question_id], $absolute = false)}}" method="post" enctype="multipart/form-data">
        @csrf
        <br><br>

        <div class="form-group ms-3 me-3">

            <label style="font-size: 18px" for="nameTextField">Answer Text</label>
            <input style="font-size: 17px" type="text" class="form-control " name="text" required id="nameTextField" placeholder="Text">
            <br>

            <label style="font-size: 18px" for="nameTextField">Answer Image</label>
            <input style="font-size: 17px" type="file" class="form-control " name="image"  placeholder="Answer">


        </div>
        <br>
        <button style="font-size: 15px" type="submit" class="btn btn-primary ms-3 me-3">Submit</button>

    </form>



@endsection
