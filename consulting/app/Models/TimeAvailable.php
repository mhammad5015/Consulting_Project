<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeAvailable extends Model
{
    use HasFactory;

    protected $table = "times_available";
    protected $fillable = ["day", "from", "to"];
    public $timestamps = false;
    public function expert()
    {
        return $this->belongsTo(Expert::class, 'expert_id');
    }
}
