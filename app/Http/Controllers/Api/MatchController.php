<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use App\Models\ProfileImage;

class MatchController extends Controller
{
    public function getMatch($email){
        $me = User::where('email', $email)->first();
        $matches = $me->matchesTo()->wherePivot('isMatch', 1)->get();
        $matchesBy = $me->matchesBy()->wherePivot('isMatch', 1)->get();

        $users = $matches->merge($matchesBy);

        $result = [];

        foreach ($users as $user) {
            $lastMessage = Message::where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $profile = ProfileImage::where('user_id', $user->id)
                ->orderBy('id', 'asc')
                ->first();

            $profileImageUrl = $profile ? asset('storage/' . $profile->path) : null;

            $result[] = [
                'user' => $user,
                'profileImage' => $profileImageUrl
            ];
        }

        return $result;
    }


    public function getMatchesBy($email){

        $me = User::where('email', $email)->first();
        $users = $me->matchesBy()->where('isMatch', '!=', 1)->get();

        $result = [];

        foreach ($users as $user) {

            $profile = ProfileImage::where('user_id', $user->id)
                ->orderBy('id', 'asc')
                ->first();

            $profileImageUrl = $profile ? asset('storage/' . $profile->path) : null;

            $result[] = [
                'user' => $user,
                'profileImage' => $profileImageUrl
            ];
        }

        return $result;
    }


    public function getMatchesTo($email){

        $me = User::where('email', $email)->first();
        $users = $me->matchesTo()->where('isMatch', '!=', 1)->get();

        $result = [];

        foreach ($users as $user) {

            $profile = ProfileImage::where('user_id', $user->id)
                ->orderBy('id', 'asc')
                ->first();

            $profileImageUrl = $profile ? asset('storage/' . $profile->path) : null;

            $result[] = [
                'user' => $user,
                'profileImage' => $profileImageUrl
            ];
        }

        return $result;
    }
}
