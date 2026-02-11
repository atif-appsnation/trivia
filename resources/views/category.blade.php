@extends('layouts.app')

@section('title')
    Category
@endsection

@section('styles')
<style>
    .category-tooltip {
        position: relative;
        display: inline-block;
    }
    .category-tooltip .tooltip-text {
        visibility: hidden;
        width: 250px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        top: 125%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .category-tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
    }
</style>
@endsection

@section('content')
    <!-- <form action="{{ route('addCategoryView', $absolute = false) }}" method="get">
        @csrf
        <div class="category-tooltip">
            <input type="submit" value="Add Category" class="btn btn-primary" style="font-size: 15px" @if(count($categories) >= 5) disabled @endif>
            @if(count($categories) >= 5)
                <div class="tooltip-text">Cannot add more categories. Maximum limit of 5 categories reached.</div>
            @endif
        </div>
    </form> -->
    <!-- temporary remove condition -->
     
    <form action="{{ route('addCategoryView', $absolute = false) }}" method="get">
        @csrf
        <div class="category-tooltip">
            <input type="submit" value="Add Category" class="btn btn-primary" style="font-size: 15px">
          
        </div>
    </form>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
    @endpush

    <div>
        <table class="table table-striped ms-3">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Activity</th>
                    <th scope="col">Image</th>
                    <th scope="col">Configuration</th>
                </tr>
            </thead>

            <tbody>
                @foreach($categories as $k => $category)

                    <tr>
                        <th scope="row">
                            <h5>{{$k + 1}}</h5>
                        </th>
                        <td>
                            <a class="navbar-brand"
                                href="{{route('question', ['category_id' => $category->id], $absolute = false)}}">
                                <h4>{{$category->name}}</h4>
                            </a>
                        </td>

                        <td>
                            @if(sizeof($category->questions) >= 24)
                                <h4>Active</h4>
                            @else
                                <h4>{{"Inactive" . sizeof($category->questions) . " / 24"}}</h4>
                            @endif
                        </td>

                        <td>
                            @if($category->image)
                                <img src="{{ asset("uploads/CategoriesImages/" . $category->image) }}"
                                    style="width : 50px; height : 50px" alt="Image">
                            @endif
                        </td>




                        <td>
                            <div>
                                <form style="float:left; margin-right: 10px"
                                    action="{{route('updateCategoryView', ['category_id' => $category->id]) }}" method="post">
                                    @csrf
                                    <input type="submit" value="Edit Category" class="btn btn-primary">
                                </form>


                                <form style="float:left;"
                                    action="{{route('deleteCategory', ['category_id' => $category->id], $absolute = false)}}"
                                    method="post">
                                    @csrf
                                    <input type="submit" value="Delete" class="btn btn-danger" style="font-size: 15px">
                                </form>

                            </div>

                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

@endsection