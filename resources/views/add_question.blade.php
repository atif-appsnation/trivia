@extends('layouts.app')

@section('title')
    Edit Category
@endsection

@section('content')


    <form action="{{route('addQuestionFun' ,['category_id'=>$category_id], $absolute = false)}}" method="post">
        @csrf
        <br><br>

        <div class="form-group ms-3 me-3">
            <label style="font-size: 17px" for="nameTextField">Question</label>
            <input style="font-size: 16px" type="text" class="form-control " name="title" required id="nameTextField" placeholder="Title">
            <br>
            <label style="font-size: 17px" for="nameTextField">Correct Answer</label>
            <input style="font-size: 16px" type="text" class="form-control " name="answer" required id="nameTextField" placeholder="Answer">


        </div>
        <br>
        <button style="font-size: 15px" type="submit" class="btn btn-primary ms-3 me-3">Submit</button>

    </form>



@endsection
