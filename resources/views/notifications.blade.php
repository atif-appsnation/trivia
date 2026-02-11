

@extends('layouts.app')

@section('title')
    Notification
@endsection


@section('content')

    <form style="float:left; margin-left: 25px"
          action="{{route('addNotificationView')}}"
          method="post">
        @csrf
        <input type="submit" value="Add Notification" class="btn btn-primary" style="font-size: 15px">
    </form>


    <div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Text</th>
                <th scope="col">Date</th>
            </tr>
            </thead>

            <tbody>
            @foreach($notifications as $k=> $notification)

                <tr>
                    <th scope="row"><h5>{{$k+1}}</h5></th>
                    <td>
                        <h4>{{$notification->text}}</h4>
                    </td>


                    <td>
                        <h4>{{$notification->date}}</h4>
                    </td>




                    <td>
                        <div>
                            <form style="float:left; margin-right: 10px"
                                  action="{{route('updateNotificationView' , ['notification_id'=>$notification->id]) }}"
                                  method="post">
                                @csrf
                                <input type="submit" value="Edit Notification" class="btn btn-primary">
                            </form>


                            <form style="float:left;"
                                  action="{{route('deleteNotification' , ['notification_id'=>$notification->id], $absolute = false)}}"
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
