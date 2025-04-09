<?php

namespace Modules\Sociallink\App\Models;

use App\Models\Category;
use App\Models\User;
use App\Traits\HasFilter;
use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Sociallink\App\Models\SocialLink;


class SocialProfile extends Model
{
    use HasFactory, UUID, HasFilter;

    protected $guarded = [];

    protected $casts = [
        'customization' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function social_links()
    {
        return $this->hasMany(SocialLink::class);
    }
}
