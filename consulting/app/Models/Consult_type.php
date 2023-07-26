<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Consult_type extends Model
{
    use HasFactory;

    protected $table = "consults_types";
    protected $primaryKey = "consult_id";
    protected $fillable = ["type"];

    public function expert()
    {
        return $this->belongsToMany(Expert::class, "ExpertConsultations");
    }
}
