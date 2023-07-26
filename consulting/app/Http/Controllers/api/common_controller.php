<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Consult_type;
use App\Models\Expert;
use App\Models\ExpertConsultations;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class common_controller extends Controller
{
    // LOGIN API
    public function login(Request $request)
    {
        // validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        // check user
        $user = User::where("email", $request->email)->first();
        if (isset($user)) {
            if (Hash::check($request->password, $user->password)) {
                // create token
                $token = $user->createToken("user_token")->plainTextToken;
                // send response
                return response()->json([
                    "status" => 1,
                    "is_expert" => 0,
                    "message" => "User Loged In Succesfully ",
                    "token" => $token,
                    "data" => $user
                ]);
            } else {
                return response()->json([
                    "status" => 0,
                    "messege" => "password didn't match"
                ]);
            }
        } else {
            $expert = Expert::where("email", $request->email)->first();
            if (isset($expert)) {
                if (Hash::check($request->password, $expert->password)) {
                    // create token
                    $token = $expert->createToken("expert_token")->plainTextToken;
                    // send response
                    return response()->json([
                        "status" => 1,
                        "is_expert" => 1,
                        "message" => "Expert Loged In Succesfully",
                        "token" => $token,
                        "data" => $expert
                    ]);
                } else {
                    return response()->json([
                        "status" => 0,
                        "message" => "Password Didn't Match"
                    ]);
                }
            }
        }
        return response()->json([
            "status" => 0,
            "message" => "Not Found"
        ]);
    }


    // LOGOUT API
    public function logout()
    {
        auth()->user()->Tokens()->delete();
        return response()->json([
            "status" => 1,
            "message" => "User Logged Out Successfully"
        ]);
    }


    // SEARCH API
    public function Search(Request $request)
    {
        $consulting = Consult_type::query();
        $expert = Expert::query();
        $search = $request->name;
        if ($search) {
            if ($consulting->where('type', 'like', '%' . $search . '%')->exists() && $expert->where('name', 'like', '%' . $search . '%')->exists()) {
                return response()->json([
                    'data' => ['experts'=> $expert->get(['expert_id','name']),'consults' => $consulting->get()]
                ]);
            } else if ($consulting->where('type', 'like', '%' . $search . '%')->exists()) {
                return response()->json([
                    'data' => ['experts'=>[],'consults'=>$consulting->get()]
                ]);
            } else if ($expert->where('name', 'like', '%' . $search . '%')->exists()) {
                return response()->json([
                    'data' => ['experts'=>$expert->get(['expert_id','name']),'consults'=>[]]
                ]);
            }
        }
        
        return response()->json([
            'message' => 'There Is No Expert or Consulting With This Name',
            'data' => []
        ],422);
    }
}
