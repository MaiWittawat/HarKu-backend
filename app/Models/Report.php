<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    // public function user(){
    //     return $this->belongsToMany(User::class);
    // }

    public function reportToUsers()
    {
        return $this->belongsToMany(User::class, 'reports', 'report_to');
    }

    public function reportByUsers()
    {
        return $this->belongsToMany(User::class, 'reports', 'report_by');
    }

}
