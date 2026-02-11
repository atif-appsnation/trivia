<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function getAllNotifications()
    {
        $notifications = Notification::all();

        if (count($notifications)){
            $response = [
                'data' => $notifications,
                'message' => 'Fetched Data Successfully',
                'status' => 200
            ];

        } else {
            $response = [
                'data' => $notifications,
                'message' => 'Fetched Data Successfully but there is no data',
                'status' => 200
            ];

        }

        return response($response, 200);
    }

    public function getNotification($id)
    {
        if ($id == null){
            $response = [
                'data' => null,
                'message' => 'Please enter id in path',
                'status' => 401
            ];
            return response($response, 401);

        }

        $notification = Notification::find($id);

        if ($notification) {
            $response = [
                'data' => $notification,
                'message' => 'Fetched Data Successfully',
                'status' => 200
            ];

            return response($response, 200);

        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);
        }

    }

    public function addNotification(Request $request)
    {
        $text = $request->text;
        $date = $request->date;

//        11/10/2022 09:39 PM

        if ($text == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter text query',
                'status' => 401
            ];
            return response($response, 401);
        }

        if ($date == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter date query',
                'status' => 401
            ];
            return response($response, 401);
        }
        $notification = Notification::create([
            'text' => $text,
            'date' => Carbon::createFromFormat('Y-m-d h:i:s',  date('Y-m-d h:i:s', strtotime($date)))
        ]);


        if ($notification) {

            return redirect()->route('notification');

        } else {

            $response = [
                'data' => null,
                'message' => 'Something went wrong',
                'status' => 401
            ];

            return response($response, 401);
        }


    }

    public function updateNotification(Request $request)
    {

        $notification_id = $request->notification_id;
        $text = $request->text;
        $date = $request->date;

        if ($notification_id == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter id query',
                'status' => 401
            ];
            return response($response, 401);
        }

        if ($date == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter date query',
                'status' => 401
            ];
            return response($response, 401);
        }

        if ($text == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter text query',
                'status' => 401
            ];
            return response($response, 401);
        }


        $notification = Notification::find($notification_id);

        if ($notification) {

            $notification->update([
                'text' => $text,
                'date' => Carbon::createFromFormat('Y-m-d h:i:s',  date('Y-m-d h:i:s', strtotime($date)))
            ]);


            return redirect()->route('notification');


        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);

        }

    }

    public function deleteNotification()
    {

        $id = request('notification_id');


        if ($id == null) {

            $response = [
                'data' => null,
                'message' => 'Please Enter id query',
                'status' => 401
            ];
            return response($response, 401);
        }

        $cat = Notification::find($id);

        if ($cat) {

            $cat->delete();


            return redirect()->route('notification');


        } else {

            $response = [
                'data' => null,
                'message' => 'Wrong Id',
                'status' => 401
            ];

            return response($response, 401);

        }
    }

    public function nofication_page()
    {
        $notifications = Notification::all();
        return view('notifications', ['notifications' => $notifications]);
    }

    public function updateNotificationView(Request $request)
    {
        $id = $request->notification_id;
        $notification = Notification::find($id);
        return view('update_notification', ['notification' => $notification]);
    }

    public function addNotificationView()
    {
        return view('add_notification');
    }

}
