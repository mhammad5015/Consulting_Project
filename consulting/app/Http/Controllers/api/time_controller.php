<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\DayAvailable;
use App\Models\Expert;
use App\Models\TimeAvailable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class time_controller extends Controller
{
    // ADDING THE AVAILABLE TIME
    public function time_available(Request $request)
    {
        // Validation
        $request->validate([
            'day' => "required|numeric|min:1|max:7",
            "from" => "required|numeric|min:1|max:24",
            "to" => 'required|numeric|min:1|max:24'
        ]);
        if ($request->from >= $request->to) {
            return response()->json([
                "status" => 0,
                "message" => "Please Enter Valid Time"
            ], 422);
        }
        $expert = Auth::user();
        $dayWorkTime = $expert->time_available;
        if (isset($dayWorkTime)) {
            foreach ($dayWorkTime as $workTimeItem) {
                if ($workTimeItem->day == $request->day) {
                    if ($request->from < $workTimeItem->to && $request->to > $workTimeItem->from) {
                        return response()->json([
                            "status" => 0,
                            "message" => "The Times Collided, Please Enter Valid Times"
                        ]);
                    }
                }
            }
        }
        // Adding The Time
        $time = new TimeAvailable();
        $time->day = $request->day;
        $time->from = $request->from;
        $time->to = $request->to;
        $time->expert_id = $expert->expert_id;
        $time->save();
        return  response()->json([
            "status" => 1,
            "message" => "Time Added Successfully",
            "data" => $time
        ]);
    }


    // GET AVAILABLE_TIME API
    public function get_available_time($localization, $expert_id)
    {
        // Validation
        $expert = Expert::where("expert_id", $expert_id)->first();
        if (!isset($expert)) {
            return response()->json([
                "status" => 0,
                "message" => "The Id is Invalid"
            ], 404);
        }
        $saturday = [];
        $sunday = [];
        $monday = [];
        $tuesday = [];
        $wednesday = [];
        $thursday = [];
        $friday = [];
        $expertAvailableTime = $expert->time_available;
        $books = Booking::where("expert_id", $expert->expert_id)->get();
        return $books;
        foreach ($expertAvailableTime as $availableTimeItem) {
            switch ($availableTimeItem->day) {
                case 1:
                    array_push($saturday, time_controller::cutting_time($availableTimeItem->from, $availableTimeItem->to, $books, 1));
                    break;
                case 2:
                    array_push($sunday, time_controller::cutting_time($availableTimeItem->from, $availableTimeItem->to, $books, 2));
                    break;
                case 3:
                    array_push($monday, time_controller::cutting_time($availableTimeItem->from, $availableTimeItem->to, $books, 3));
                    break;
                case 4:
                    array_push($tuesday, time_controller::cutting_time($availableTimeItem->from, $availableTimeItem->to, $books, 4));
                    break;
                case 5:
                    array_push($wednesday, time_controller::cutting_time($availableTimeItem->from, $availableTimeItem->to, $books, 5));
                    break;
                case 6:
                    array_push($thursday, time_controller::cutting_time($availableTimeItem->from, $availableTimeItem->to, $books, 6));
                    break;
                case 7:
                    array_push($friday, time_controller::cutting_time($availableTimeItem->from, $availableTimeItem->to, $books, 7));
                    break;
            }
        }
        // Sending response
        return response()->json([
            "saturday" => $saturday,
            "sunday" => $sunday,
            "monday" => $monday,
            "tuesday" => $tuesday,
            "wednesday" => $wednesday,
            "thursday" => $thursday,
            "friday" => $friday
        ], 200);
    }


    // CUTTING THE AVAILABLE TIME
    private function cutting_time($from, $to, $books, $day)
    {
        $result = [];
        while ($to > $from + 1) {
            $booked = false;    
            foreach ($books as $book) {
                if ($book->day == $day && $book->from == $from + 1) {
                    $booked = true;
                    break;
                }
            }
            if (!$booked) {
                array_push($result, $from);
            }
            $from = $from + 1;
            if ($from == $to - 1) {
                array_push($result, $from);
            }
        }
        return $result;
    }


    // BOOKING API
    public function booking(Request $request, $localization, $expert_id)
    {
        // Simple Validation
        $expert = Expert::where('expert_id', $expert_id)->first();
        if (!isset($expert)) {
            return response()->json([
                "status" => 0,
                "message" => "The Id is Invalid"
            ], 404);
        }
        $request->validate([
            "day" => 'required|numeric|min:1|max:7',
            "from" => 'required|numeric|min:1|max:23'
        ]);
        // check if the The appointment already booked
        $books = Booking::get();
        foreach ($books as $item) {
            if ($item->day == $request->day && $item->from == $request->from) {
                return response()->json([
                    "status" => 0,
                    "message" => "The appointment already booked"
                ]);
            }
        }
        // Updating the wallets
        $user = auth()->user();
        $User = User::where('user_id', $user->user_id)->first();
        if ($User->user_wallet >= $expert->session_price) {
            // reduce the user balance
            $User->user_wallet = $User->user_wallet - $expert->session_price;
            // add the balance to expert
            $expert->expert_wallet = $expert->expert_wallet + $expert->session_price;
            // saving the wallets in database
            $User->save();
            $expert->save();
        } else {
            return response()->json([
                "status" => "0",
                "message" => "You dont have enough balance in your wallet"
            ]);
        }
        // Storing the appointment in the booking table
        $book = new Booking();
        $book->expert_id = $expert->expert_id;
        $book->user_id = $User->user_id;
        $book->day = $request->day;
        $book->from = $request->from;
        $book->save();
        // Sending Response
        return response()->json([
            "message" => "booked successfully",
            "book" => $book
        ], 200);
    }


    // GET THE APPOINTMENTS API
    public function get_booked_times()
    {
        $expert = auth()->user();
        $books = Booking::where("expert_id", $expert->expert_id)->get();
        return response()->json([
            "data" => $books
        ], 200);
    }
}
