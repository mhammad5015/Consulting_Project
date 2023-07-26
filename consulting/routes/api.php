<?php

use App\Http\Controllers\api\common_controller;
use App\Http\Controllers\api\experts_controller;
use App\Http\Controllers\api\time_controller;
use App\Http\Controllers\Api\users_controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// without authentication
Route::middleware('SetAppLang')->prefix('{local}')->group(function () {
    Route::post("user_regester", [users_controller::class, "user_regester"]);
    Route::post("expert_regester", [experts_controller::class, "expert_regester"]);
    Route::post("login", [common_controller::class, "login"]);
});

// using authentication
// Route::prefix('{local}')->group(["middleware" => ['auth:sanctum', 'SetAppLang']], function () {
Route::middleware(['auth:sanctum', 'SetAppLang'])->prefix('{local}')->group(function () {
    // user controller
    Route::get("user_profile", [users_controller::class, "user_profile"]);
    Route::post("charge", [users_controller::class, "charge"]);
    Route::post("add_to_favorites/{expert_id}", [users_controller::class, "add_to_favorites"]);
    Route::get("get_all_favorites", [users_controller::class, "get_all_favorites"]);
    Route::post("rate/{expert_id}", [users_controller::class, "rate"]);
    Route::get("show_rate/{expert_id}", [users_controller::class, "show_rate"]);
    // expert controller
    Route::get("profile/{id}", [experts_controller::class, "expert_by_id"]);
    Route::get("expert-by-consultatoin/{consult_id}", [experts_controller::class, "ExpertsByConsultation"]);
    Route::get("get_all_experts", [experts_controller::class, "get_all_experts"]);
    Route::put("add_consultion", [experts_controller::class, "add_consultion"]);
    // common controller
    Route::get("logout", [common_controller::class, "logout"]);
    Route::post("search", [common_controller::class, "Search"]);
    // time controller
    Route::post("time_available", [time_controller::class, "time_available"]);
    Route::post("booking/{expert_id}", [time_controller::class, "booking"]);
    Route::get("get_available_time/{expert_id}", [time_controller::class, "get_available_time"]);
    Route::get("get_booked_times", [time_controller::class, "get_booked_times"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
