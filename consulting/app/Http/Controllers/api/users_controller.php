<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Expert;
use App\Models\Favorite;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Json;

use function PHPUnit\Framework\isEmpty;

class users_controller extends Controller
{
    // REGESTER API
    public function user_regester(Request $request)
    {
        // checking the email if the expert already take the email
        $expert = Expert::where("email", $request->email)->first();
        if (isset($expert)) {
            return response()->json([
                "status" => 0,
                "message" => __('message.The email has already been taken')
            ]);
        }
        // validation
        $request->validate([
            "name" => "required|alpha_dash",
            "email" => "required|unique:users|email",
            "password" => "required|confirmed"
        ]);
        // create data
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->user_wallet = 1000;
        $user->save();
        $token = $user->createToken("token_name")->plainTextToken;
        // Sending response
        return response()->json([
            "status" => 1,
            "message" => __('message.User Created Sccessfully'),
            "token" => $token, 
            "data" => $user
        ]);
    }


    // BALANCE CHARGING API
    public function charge(Request $request)
    {
        // Validation
        $request->validate(["balance" => 'required|numeric|min:1|max:100000']);
        // Updating
        $user = auth()->user();
        $User = User::find($user->user_id);
        $User->user_wallet = $User->user_wallet + $request->balance;
        $User->save();
        // Sending response
        return response()->json([
            "status" => "1",
            "message" => __("message.balance changed sccessfully"),
            "wallet" => $User->user_wallet
        ]);
    }


    // ADD TO FAVORITES LIST API
    public function add_to_favorites(Request $request, $localization, $expert_id)
    {
        $user = auth()->user();
        $favorites = Favorite::get();
        foreach ($favorites as $item) {
            if ($item->user_id == $user->user_id && $item->expert_id == $expert_id) {
                return response()->json([
                    "status" => 0,
                    "message" => __('message.The expert already in your favorites list.'),
                ]);
            }
        }
        $favorite = Favorite::create([
            "expert_id" => $expert_id,
            "user_id" => $user->user_id
        ]);
        return response()->json([
            "status" => 1,
            "message" => __("message.Expert added successfully to your favorites list."),
            "data" => $favorite
        ], 200);
    }


    // GET ALL THE FAVORITES API
    public function get_all_favorites(Request $request)
    {
        $data = Expert::join("favorites", "experts.expert_id", "=", "favorites.expert_id")->where("favorites.user_id", "=", auth()->user()->user_id)->get(['experts.expert_id', 'experts.name']);
        if (isEmpty($data)) {
            return response()->json([
                "message" => __("message.There is no experts in your favorites."),
                "data" => $data
            ]);
        }
        return response()->json([
            "data" => $data
        ]);
    }


    // RATING THE EXPERT API
    public function rate(Request $request, $id)
    {
        $data = Validator::make($request->all(), [
            'rate' => 'required|integer|min:1|max:5'
        ]);
        if ($data->fails()) {
            return response()->json(['message' => $data->errors()]);
        }
        $user = auth()->user();
        $ratee = Rate::get();
        foreach ($ratee as $item) {
            if ($item->user_id == $user->user_id && $item->expert_id == $id) {
                $r = Rate::query()->where('user_id', Auth::user()->user_id);
                $r->update($request->only('rate'));
                return Response()->json([
                    "status" => 1,
                    "message" => __("message.Rating updated successfully")
                ], 200);
            }
        }
        $rate = Rate::create([
            'rate' => $request['rate'],
            'expert_id' => $id,
            'user_id' => Auth::user()->user_id
        ]);
        return Response()->json([
            "status" => 1,
            "message" => __("message.Rating added successfully")
        ], 200);
    }


    // SHOWING THE RATING STARS API
    public function show_rate(Request $request, $id)
    {
        $sumRate = Rate::where('expert_id', $id)->sum('rate');
        $sumExpert = Rate::where('expert_id', $id)->count();
        if ($sumExpert != 0) {
            $rates = $sumRate / $sumExpert;
            return Response()->json([
                "status" => 1,
                'Evaluation_Rate' => $rates
            ], 200);
        } else {
            return Response()->json([
                "status" => 1,
                'rate' => 0
            ], 200);
        }
    }


    // get user data
    public function user_profile(Request $request)
    {
        return auth()->user();
    }
}
