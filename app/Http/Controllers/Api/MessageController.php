<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\ProfileImage;

class MessageController extends Controller
{

    public function getMessage(Request $request)
    {
        $sender_id = $request->input('sender_id');
        $receiver_id = $request->input('receiver_id');

        // $messages = Message::where('sender_id', $sender_id)
        //     ->where('receiver_id', $receiver_id)
        //     ->get();

        $messages = Message::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id);
        })
            ->orWhere(function ($query) use ($sender_id, $receiver_id) {
                $query->where('sender_id', $receiver_id)
                    ->where('receiver_id', $sender_id);
            })
            ->get();

        return response()->json($messages);
    }

    public function storeMessage(Request $request)
    {
        $request->validate([
            'sender_id' => ['required'],
            'receiver_id' => ['required'],
            'body' => ['required', 'string']
        ]);

        $message = new Message([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);
        $message->save();


        return response()->json('store message success!');
    }


    public function lastMessageIndex()
    {
        $lastMessageIndex = Message::orderBy('id', 'desc')->first()->id;
        return $lastMessageIndex;
        // return response()->json('success');
    }


    public function lastMessage($sender_id, $receiver_id)
    {
        // ค้นหาล่าสุดของข้อความระหว่างผู้ส่งและผู้รับ
        $lastMessage = Message::where(function ($query) use ($sender_id, $receiver_id) {
            $query->where('sender_id', $sender_id)
                ->where('receiver_id', $receiver_id);
        })
            ->orWhere(function ($query) use ($sender_id, $receiver_id) {
                $query->where('sender_id', $receiver_id)
                    ->where('receiver_id', $sender_id);
            })
            ->orderBy('created_at', 'desc') // เรียงลำดับตาม created_at ใหม่ที่สุดขึ้นก่อน
            ->first(); // เรียกหาเพียงข้อความเดียว

        return $lastMessage;
    }

    public function getChatList($email)
    {
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
                'lastMessage' => $lastMessage,
                'profileImage' => $profileImageUrl
            ];
        }

        return $result;
    }


    public function markAsRead(Message $message)
    {
        $message->update(['read' => 1]);
        return response()->json(['message' => 'Message marked as read']);
    }
}
