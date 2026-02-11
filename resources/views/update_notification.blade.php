
@extends('layouts.app')

<html>

<head>

    @section('title')
        Update Notification
    @endsection
</head>

<body>

@section('content')

    <form action="{{route('updateNotificationFun' , ['notification_id'=>$notification->id , 'text'=>$notification->text , 'date'=>$notification->date] , $absolute = false)}}" method="post" enctype="multipart/form-data">
        @csrf
        <br>

        <div class="form-group ms-3 me-3">
            <label for="nameTextField">Notification Text</label>
            <br>
            <input type="text" value="{{$notification->text}}" class="form-control " name="text" required id="nameTextField" placeholder="name">
        </div>
        <br>

        <div class="form-group ms-3 me-3">
            <label for="nameTextField">Notification Date</label>
            <br>
	    <input type="datetime-local" value="{{$notification->date}}" name="date" class="form-control" required placeholder="Date">
       </div>

        <br>
        <br>

        <div class="text-center">

            <input type="submit" class="btn btn-primary ms-3 me-3" style="width: 90%" value="Submit">

            <br>
            <br>


        </div>

    </form>



@endsection

</body>
</html>




