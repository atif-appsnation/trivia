@extends('layouts.app')

<html>


<head>

    @section('title')
        Add Notification
    @endsection
</head>

<body>

@section('content')


    <form action="{{route('addNotificationFun' , $absolute = false)}}" method="post" enctype="multipart/form-data">
        @csrf
        <br>

        <label for="nameTextField" style="margin-left: 1%; width: 98%">Notification Text</label>
        <br>
        <input type="text" class="form-control" name="text" required id="nameTextField" placeholder="Text" style="margin-left: 1%; width: 98%">

        <br><br>


        <label for="nameTextField" style="margin-left: 1%; width: 98%">Notification Date</label>

        <input type="datetime-local"  name="date" class="form-control" required placeholder="Date" style="margin-left: 1%; width: 98%">

        <br>

        <br>

        <br>
        <div class="text-center">

            <input type="submit" class="btn btn-primary ms-3 me-3" style="width: 90%" value="Submit">

            <br>
            <br>


        </div>

    </form>


</body>




</html>



















@endsection
