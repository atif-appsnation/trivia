@extends('layouts.app')

@section('title')
    Edit Category
@endsection

@section('content')

    <form action="{{route('updateCategoryFun' , ['category_id'=>$category->id , 'name'=>$category->name] , $absolute = false)}}" method="post" enctype="multipart/form-data">
        @csrf
        <br>

        <div class="form-group ms-3 me-3">
            <label for="nameTextField">Category Name</label>
            <br>
            <input type="text" value="{{$category->name}}" class="form-control " name="name" required id="nameTextField" placeholder="name">
        </div>
        <br>

            <div class="form-group">
                <label >Category Image</label>
                <input type="file" class="form-control " name="image"  placeholder="Category Image">
            </div>

        <br>
        <br>

        @if($category->image)
            <img src="{{ asset("uploads/CategoriesImages/".$category->image) }}"  style="margin-left: 39%; width : 400px; height : 400px" alt="Image"  >
        @endif

        <br>
        <br>


        <div class="text-center">

            <input type="submit" class="btn btn-primary ms-3 me-3" style="width: 90%" value="Submit">

            <br>
            <br>


        </div>

    </form>



@endsection
