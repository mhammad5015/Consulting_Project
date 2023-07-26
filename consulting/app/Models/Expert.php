<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class Expert extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = "experts";
    protected $primaryKey = "expert_id";
    protected $fillable = [
        "name",
        "email",
        "password",
        "phone_num",
        "img",
        "address",
        "experience",
        "expert_wallet",
        "session_time",
        "session_price"
    ];
    public $timestamps = false;

    // consultations
    public function consult_type()
    {
        return $this->belongsToMany(Consult_type::class, "ExpertConsultations");
    }

    /**
     * Get all of the comments for the Expert
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ExpertConsultations()
    {
        return $this->hasMany(ExpertConsultations::class, 'expert_id');
    }
    // booking
    public function user()
    {
        return $this->belongsToMany(User::class, "Booking");
    }
    // day available
    public function time_available()
    {
        return $this->hasMany(TimeAvailable::class, "expert_id");
    }
}
