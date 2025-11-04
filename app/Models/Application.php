<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'opportunity_id',
        'youth_profile_id',
        'status',
        'cover_letter',
        'applied_on',
    ];

    protected $casts = [
        'applied_on' => 'date',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function youthProfile()
    {
        return $this->belongsTo(YouthProfile::class);
    }

    public function user()
    {
        // Access user through youthProfile relationship
        return $this->hasOneThrough(User::class, YouthProfile::class, 'id', 'id', 'youth_profile_id', 'user_id');
    }
}
