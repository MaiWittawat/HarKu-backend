<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;

class UserController extends Controller
{
    public function test()
    {
        return "success";
    }

    public function registeration(Request $request)
    {

        dd($request->all());

        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'gender' => ['required', 'string'],
            'show_gender' => ['required', 'string'],
            'birthday' => ['required'],
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

        // $user = new User();
        // $user->name = $request->name;
        // $user->email = $request->email;
        // $user->password = $request->password;

        // $userInfo = new UserInfo();
        // $userInfo->birthday = $request->birthday;
        // $userInfo->age = $request->age;
        // $userInfo->height = $request->height;
        // $userInfo->gender = $request->gender;
        // $userInfo->show_gender = $request->show_gender;
        // $userInfo->relation = $request->relation;
        // $userInfo->education = $request->education;
        // $userInfo->smoking = $request->smoking;
        // $userInfo->drinking = $request->drinking;
        // $userInfo->about_me = $request->about_me;
        // $userInfo->first_date_idea = $request->first_date_idea;

        // $user->info()->save($userInfo);

        // การใช้ create จะสร้างและบันทึกลงในฐานข้อมูล
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $userInfo = UserInfo::create([
            'user_id' => $user->id,
            'birthday' => $request->birthday,
            'age' => $request->age,
            'height' => $request->height,
            'gender' => $request->gender,
            'show_gender' => $request->show_gender,
            'relation' => $request->relation,
            'education' => $request->education,
            'smoking' => $request->smoking,
            'drinking' => $request->drinking,
            'about_me' => $request->about_me,
            'first_date_idea' => $request->first_date_idea,
        ]);


        return response()->json($user);
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
            abort(400, "Email is emtry.");
        }

        $existEmail = User::where('email', $email)->first();

        if ($existEmail == NULL) {
            abort(400, "Email is valid");
        }

        $userData = User::with('info')->where('email', '!=', $email)->get();

        return response()->json($userData);
    }
}
