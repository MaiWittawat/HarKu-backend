<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Interest;


class UserController extends Controller
{
    public function test()
    {
        return "success";
    }

    public function registeration(Request $request)
    {
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
            'education'=> ['required', 'string'],
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
        foreach($request->interests as $name) {
            $interest = Interest::where('name', '=', $name)->first();
            if($interest == NULL) {
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

        foreach($listOfInterrest as $interest) {
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

        $userEmail = $request->email;

        $user = User::with('info.interests')->where('email', $userEmail)->first();

        return response()->json($user);
    }


    public function getUserForMatch($email)
    {
        if($email == null || $email == ""){
            abort(400, "Email is empty.");
        }

        $existEmail = User::where('email', $email)->first();

        if ($existEmail == NULL) {
            abort(400, "Email is invalid");
        }

        $me = User::with('info')->where('email', '=', $email)->first();
        $me = $me->info()->first();
        $userData = User::with('info.interests')
                        ->where('email', '!=', $email)
                        ->get();

        $userData = $userData->shuffle();
        $list = array();
        foreach($userData as $user) {
            $dis = $me->calDistance(floatval($user->info->latitude), floatval($user->info->longitude));
            array_push($list, [ "user" => $user, "distance" => $dis]);
        }

        return response()->json($list);
    }

    public function getInterests() {
        return Interest::get();
    }

    public function changePassword(Request $request) {
        $request->validate([
            'email' => ['required', 'email'],
            'newPassword' => ['required', 'string'],
            'oldPassword' => ['required', 'string']
        ]);

        if($request->email == null || $request->email == ""){
            abort(400, "Email is empty.");
        }

        $user = User::where('email', $request->email)->first();

        if ($user == NULL) {
            abort(400, "Email is invalid");
        }

        if(password_verify($request->oldPassword, $user->password)) {
            $user->password = $request->newPassword;
            $user->save();
            return response("change password successfully");
        } else {
            abort(400, "Password is incorrect");
        }

    }
}
