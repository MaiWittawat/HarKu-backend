<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Passion;


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
        ]);

        $email = $request->get('email');
        $exist = User::where('email', $email)->first();


        if ($exist !== NULL) {
            abort(400, "Email '{$email}' has been used.");
            // return response()->json();
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

        $user->info()->save($userInfo);

        return response("crate user success");
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

        $user = User::where('email', $userEmail)->first();

        return response()->json($user);
    }


    public function getUserForMatch($email)
    {
        if($email == null || $email == ""){
            abort(400, "Email is empty.");
        }

        $existEmail = User::where('email', $email)->first();

        if ($existEmail == NULL) {
            abort(400, "Email is valid");
        }

        $me = User::with('info')->get()->where("email", $email);

        $userData = User::with('info')->where('email', '!=', $email)->get();
        // $userData = User::with('info')
        //                 ->where('email', '!=', $email)
        //                 ->where("")
        //                 ->get();


        return response()->json($userData);
    }

    public function getPassions() {
        return Passion::get();
    }
}
