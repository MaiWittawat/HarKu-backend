<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProfileImage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserInfo extends Model
{
    use HasFactory;

    //relation
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile_images(): HasMany {
        return $this->hasMany(ProfileImage::class);
    }

    public function passions(): BelongsToMany {
        return $this->belongsToMany(Passion::class);
    }

    //methods
    public function isPassionFull() {
        if($this->passions()->count() <= 5) {
            return false;
        }
        return true;
    }

    public function getAge() {
        $age = date_diff(date_create($this->birthday), date_create('now'))->y;
        return $age;
    }
}
