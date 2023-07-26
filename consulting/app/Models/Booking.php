<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = "booking";
    protected $fillable = ["from","day"];
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
