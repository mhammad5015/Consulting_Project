<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExpertConsultations extends Pivot
{
    use HasFactory;

    protected $table = "expert_consultations";
    public $timestamps = false;
    protected $fillable = [
        "consult_id",
        "expert_id"
    ];
}
