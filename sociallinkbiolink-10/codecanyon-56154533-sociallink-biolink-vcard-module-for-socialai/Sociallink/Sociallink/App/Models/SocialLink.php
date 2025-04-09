<?php

namespace Modules\Sociallink\App\Models;

use App\Models\User;
use App\Traits\HasFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SocialLink extends Model
{
    use HasFactory, HasFilter;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function social_profile()
    {
        return $this->belongsTo(SocialProfile::class);
    }
}
