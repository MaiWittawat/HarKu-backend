<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\ProfileImage;
use App\Models\Interest;
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

    public function interests(): BelongsToMany {
        return $this->belongsToMany(Interest::class);
    }

    //methods
    public function isInterestsFull() {
        if($this->interests()->count() <= 5) {
            return false;
        }
        return true;
    }

    public function getAge() {
        $age = date_diff(date_create($this->birthday), date_create('now'))->y;
        return $age;
    }

    public function calDistance($latitudeTo, $longitudeTo) {
        $earthRadius = 6371; // Radius of the Earth in kilometers
    
        $dLat = deg2rad($latitudeTo - $this->latitude);
        $dLon = deg2rad($longitudeTo - $this->longitude);
    
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($this->latitude)) * cos(deg2rad($latitudeTo)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earthRadius * $c;
    
        return $distance;
    }
}
