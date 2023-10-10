<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function test(){
        return "success";
    }

    public function registeration(Request $request){

        // dd($request->all());

        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required','email'],
            'password' => ['required'],
            'gender' => ['required', 'string'],
            'show_gender' => ['required', 'string'],
            'birthday' => ['required'],
        ]);

        $email = $request->get('email');
        $exist = User::where('email', $email)->first();


        if($exist !== NULL){
            abort(400,"Email '{$email}' has been used.");
            // return response()->json();
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->gender = $request->gender;
        $user->show_gender = $request->show_gender;
        $user->birthday = $request->birthday;

        $user->save();

        // return response()->json($user);
        return $user;

    }
}
