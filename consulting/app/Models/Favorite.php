<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Favorite extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "favorites";
    protected $fillable = ["expert_id", "user_id"];
    public $timestamps = false;
    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
