<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;

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




        // return response()->json("success");

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


    public function lastMessageIndex(){
        $lastMessageIndex = Message::orderBy('id', 'desc')->first()->id;
        return $lastMessageIndex;
        // return response()->jso   n('success');
    }


    public function lastMessage($sender_id, $receiver_id){
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

}
