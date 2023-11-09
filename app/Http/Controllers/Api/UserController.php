<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Interest;
use Carbon\Carbon;
use App\Models\ProfileImage;

class UserController extends Controller
{
    public function test()
    {
        return "success";
    }

    public function registeration(Request $request)
    {
        // return response($request->all());
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'gender' => ['required', 'string'],
            'show_gender' => ['required', 'string'],
            'birthday' => ['required', 'string'],
            'max_age' => ['required', 'integer'],
            'min_age' => ['required', 'integer'],
            'distance' => ['required', 'integer'],
            'about_me' => ['required', 'string'],
            'drinking' => ['required', 'string'],
            'education' => ['required', 'string'],
            'height' => ['required', 'integer'],
            'relation' => ['required', 'string'],
            'smoking' => ['required', 'string'],
            'longitude' => ['required', 'numeric'],
            'latitude' => ['required', 'numeric'],
            'interests' => 'required|array',
            'interests.*' => 'string'
        ]);

        $email = $request->get('email');
        $exist = User::where('email', $email)->first();


        if ($exist !== NULL) {
            abort(400, "Email '{$email}' has been used.");
        }

        $listOfInterrest = array();
        foreach ($request->interests as $name) {
            $interest = Interest::where('name', '=', $name)->first();
            if ($interest == NULL) {
                abort(400, "Interest is invalid");
            }
            array_push($listOfInterrest, $interest);
        }

        if ($request->hasFile('image')) {
            return "hasfile";
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;

        $user->save();

        $userInfo = new UserInfo();
        $userInfo->user_id = $user->id;
        $userInfo->birthday = $request->birthday;
        $userInfo->height = $request->height;
        $userInfo->gender = $request->gender;
        $userInfo->prefer_min_age = $request->min_age;
        $userInfo->prefer_max_age = $request->max_age;
        $userInfo->show_gender = $request->show_gender;
        $userInfo->relation = $request->relation;
        $userInfo->education = $request->education;
        $userInfo->smoking = $request->smoking;
        $userInfo->drinking = $request->drinking;
        $userInfo->about_me = $request->about_me;
        $userInfo->longitude = $request->longitude;
        $userInfo->latitude = $request->latitude;
        $userInfo->distance = $request->distance;

        $user->info()->save($userInfo);

        foreach ($listOfInterrest as $interest) {
            $userInfo->interests()->save($interest);
        }

        return response("create user success");
    }

    public function getAllUser(Request $request)
    {

        $request->validate([
            'userId' => ['required']
        ]);

        $userIdToExclude = $request->userId;

        $users = User::where('id', '!=', $userIdToExclude)->get();

        return response()->json($users);
    }



    public function getUser(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $userData = array();
        $userEmail = $request->email;

        $user = User::with('info.interests')->where('email', $userEmail)->first();

        $profileImage = $user->profileImages()->first();

        $imageUrl = "";

        $me = UserInfo::where('user_id', $user->id)->first();
        $age = $me->getAge();

        if ($profileImage == null) {
            $imageUrl = "";
        } else {
            $imageUrl = asset('storage/' . $profileImage->path);
        }


        array_push($userData, ["user" => $user, "image" => $imageUrl, "age" => $age]);

        return response()->json($userData);
    }


    public function getUserForMatch($email)
    {

        if ($email == null || $email == "") {
            abort(400, "Email is empty.");
        }

        $existEmail = User::where('email', $email)->first();


        if ($existEmail == NULL) {
            abort(400, "Email is invalid");
        }

        $me = User::with('info')->where('email', $email)->first();

        $age = $me->info->getAge();

        $userData = User::with('info.interests')
            ->where('email', '!=', $email)
            ->whereDoesntHave('matchesBy', function ($query) use ($me) {
                $query->where('user_user.match_by', $me->id);
            })
            ->whereDoesntHave('matchesTo', function ($query) use ($me) {
                $query->where('user_user.isMatch', 1);
            })
            ->inRandomOrder()
            ->get();

        $list = array();
        $me = $me->info()->first();
        foreach ($userData as $user) {
            $dis = $me->calDistance(floatval($user->info->latitude), floatval($user->info->longitude));
            $profileImages = $user->profileImages()->get();

            $imageUrls = $profileImages->map(function ($profileImage) {
                return asset('storage/' . $profileImage->path);
            });

            array_push($list, ["user" => $user, "distance" => $dis, "profileImage" => $imageUrls, "age"=> $age]);
        }

        return response()->json($list);
    }

    public function getInterests()
    {
        return Interest::get();
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'newPassword' => ['required', 'string'],
            'oldPassword' => ['required', 'string']
        ]);

        if ($request->email == null || $request->email == "") {
            abort(400, "Email is empty.");
        }

        $user = User::where('email', $request->email)->first();

        if ($user == NULL) {
            abort(400, "Email is invalid");
        }

        if (password_verify($request->oldPassword, $user->password)) {
            $user->password = $request->newPassword;
            $user->save();
            return response("change password successfully");
        }

        abort(400, ['error'=>"Password is incorrect"]);
    }

    public function editProfile(Request $request)
    {

        // return $request->all();

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user == null) {
            abort(400, "user not found");
        }

        $profileImages = $user->profileImages()->get();

        if($request->hasFile('images')){
            foreach ($profileImages as $img) {
                $img->delete();
            }

            foreach ($request->images as $profileImg) {
                $image = new ProfileImage();
                $image->user_id = $user->id;
                $image->path = $profileImg;
                $image->save();
            }
        }


        // foreach($request->interests as $interest){
        //     $userInterest = new Interest();
        //     $userInterest->userinfo_id
        // }



        $profile = UserInfo::where('user_id', $user->id)->first();

        $profile->smoking = $request->smoking ?? $profile->smoking;
        $profile->drinking = $request->drinking ?? $profile->drinking;
        $profile->about_me = $request->about_me ?? $profile->about_me;
        $profile->height = $request->height ?? $profile->height;
        $profile->relation = $request->relation ?? $profile->relation;
        $profile->education = $request->education ?? $profile->education;

        $profile->save();

        return response("change profile success");
    }

    public function like(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'likeTo' => ['required', 'integer'], // user_id that the person you like
        ]);

        if ($request->email == null || $request->email == "") {
            abort(400, "Email is empty.");
        }

        $me = User::where('email', $request->email)->first();
        $user = User::with('matchesBy')->where('id', $request->likeTo)->first();

        if ($me == NULL) {
            abort(400, "Email is invalid");
        }

        //Did you match this person before?
        if ($me->matchesTo->contains('id', $user->id) || $user->matchesBy->contains('id', $me->id)) {
            return response("You have already like or it was a match");
        }

        //Am I match by this user?
        if ($me->matchesBy->contains('id', $user->id)) {
            $me->matchesBy()->updateExistingPivot($user, ['isMatch' => true]);
            return response('It is a match');
        } else {
            $me->matchesTo()->attach($user);
            return response('You have matched');
        }
    }

    public function isMatch(Request $request)
    {
        $request->validate([
            'sender_id' => ['required', 'integer'],
            'receiver_id' => ['required', 'integer']
        ]);

        $user = User::with(['matchesBy', 'matchesTo'])->find($request->sender_id);
        if ($user->matchesTo->contains('id', $request->receiver_id)) {
            return response()->json(['matchesTo' => $request->receiver_id, 'matchesBy' => $request->sender_id]);
        } elseif ($user->matchesBy->contains('id', $request->receiver_id)) {
            return response()->json(['matchesTo' => $request->sender_id, 'matchesBy' => $request->receiver_id]);
        } else {
            abort(400, "Is not matched yet!");
        }
    }

    public function myMatch(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email']
        ]);

        if ($request->email == null || $request->email == "") {
            abort(400, "Email is empty.");
        }

        $user = User::with(['matchesBy', 'matchesTo'])->find('email', $request->email);

        return response()->json();
    }


    public function online(Request $request){
        $request->validate([
            'email' => ['required','email']
        ]);
        $me = User::where('email', $request->email)->first();
        $me->last_seen = null;
    }

    public function updateStatus(Request $request){
        $request->validate([
            'email' => ['required','email']
        ]);
        $me = User::where('email', $request->email)->first();
        $me->last_seen = now();

        $me->save();

        return "Logout success";
    }
}
