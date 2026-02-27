<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use RTippin\Janus\Facades\Janus;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Models\ChatMessage;

class ChatAppController extends BaseController
{

    public function index(){
        $pageTitle = __("Chat");
        $user = auth()->user();
        $default = true;
        return view('apps.chat',compact(
            'user','pageTitle','default'
        ));
    }

    public function destroy($receiver)
    {
       $messages = ChatMessage::where(function ($q) use ($receiver) {
            $q->where('from_id', auth()->id())
            ->where('receiver_id', $receiver);
        })
        ->orWhere(function ($q) use ($receiver) {
            $q->where('from_id', $receiver)
            ->where('receiver_id', auth()->id());
        })
        ->pluck('id')
        ->all();
        ChatMessage::destroy($messages);
        $notification = notify(__('Conversation has been deleted'));
        return redirect()->route('app.chat')->with($notification);
    }
}
