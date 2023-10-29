<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserInfo;

class Passion extends Model
{
    use HasFactory;

    public function users() {
        return $this->BelongsToMany(UserInfo::class);
    }
}
