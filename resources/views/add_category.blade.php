@extends('layouts.app')

@section('title')
    Edit Category
@endsection

@section('content')



    <form action="{{route('addCategoryFun' , $absolute = false)}}" method="post" enctype="multipart/form-data">
        @csrf
        <br><br>


        <label for="nameTextField" style="margin-left: 1%; width: 98%">Category Name</label>
        <br>
        <input type="text" class="form-control " name="name" required id="nameTextField" placeholder="name" style="margin-left: 1%; width: 98%">

        <br><br>

        <label style="font-size: 15px; margin-left: 1%; width: 98%" for="image" >Category Image</label>
        <input style="font-size: 17px; margin-left: 1%; width: 98%" type="file" class="form-control " name="image"  placeholder="Category image">


        <br>
        <button type="submit" class="btn btn-primary ms-3 me-3">Submit</button>

    </form>



@endsection
