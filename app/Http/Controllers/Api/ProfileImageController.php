<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProfileImage;

class ProfileImageController extends Controller
{
    public function uploadFile(Request $request)
    {
        $me = User::where('email', $request->email)->first();

        if ($me != null) {
            if ($request->hasFile('image')) {

                $uploadedImageUrls = [];

                foreach ($request->file('image') as $image) {
                    $imagePath = $image->store('images', 'public');

                    $imageProfile = new ProfileImage();
                    $imageProfile->path = $imagePath;
                    $imageProfile->user_id = $me->id;
                    $imageProfile->save();

                    $imageUrl = asset('storage/' . $imagePath);
                    $uploadedImageUrls[] = $imageUrl;
                }

                return response()->json(200);
            }
            return response()->json(['error' => 'No files uploaded'], 400);
        }

        return response()->json(['error' => 'User not found'], 400);
    }

    public function editFile(Request $request)
    {


        $me = User::where('email', $request->email)->first();

        if ($me != null) {

            if ($request->hasFile('image')) {
                ProfileImage::where('user_id', $me->id)->delete();

                $uploadedImageUrls = [];

                foreach ($request->file('image') as $image) {
                    $imagePath = $image->store('images', 'public');

                    $imageProfile = new ProfileImage();
                    $imageProfile->path = $imagePath;
                    $imageProfile->user_id = $me->id;
                    $imageProfile->save();

                    $imageUrl = asset('storage/' . $imagePath);
                    $uploadedImageUrls[] = $imageUrl;
                }

                return response()->json(200);
            }
            return response()->json(['error' => 'No files uploaded'], 400);
        }

        return response()->json(['error' => 'User not found'], 400);
    }


    public function getProfileImages($email)
    {
        if ($email == "" || $email == null) {
            abort(400, "Email is empty.");
        }

        $me = User::where('email', $email)->first();

        if ($me != null) {
            $imageProfiles = ProfileImage::where('user_id', $me->id)->get();

            $imageUrls = $imageProfiles->map(function ($imageProfile) {
                return asset('storage/' . $imageProfile->path);
            });

            if ($imageUrls->isNotEmpty()) {
                return $imageUrls;
            }

            return $imageUrls;
        }
        return response()->json(['error' => 'User not found'], 400);
    }


    public function getImage($email)
    {

        if ($email == "" || $email == null) {
            abort(400, "Email is empty.");
        }

        $me = User::where('email', $email)->first();

        if ($me != null) {

            $image = ProfileImage::where('user_id', $me->id)
                ->orderBy('id', 'asc')
                ->first();

            $imageUrl = $image ? asset('storage/' . $image->path) : null;

            return response()->json($imageUrl);
        }
        return response()->json(['error' => 'User not found'], 400);
    }
}
