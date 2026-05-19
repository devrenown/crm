<?php

namespace App\Livewire\Apps\Chat;

use App\Models\User;
use Livewire\Component;
use App\Models\ChatMessage;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{

    public $selectedUser;

    public $searchQuery;


    // public function getChats()
    // {
    //     $chats = ChatMessage::join('users',  function ($join) {
    //         $join->on('chat_messages.user_id', '=', 'users.id')
    //             ->orOn('chat_messages.receiver_id', '=', 'users.id');
    //     })
    //     ->where(function ($q) {
    //         $q->where('chat_messages.user_id', Auth::user()->id)
    //         ->orWhere('chat_messages.receiver_id', Auth::user()->id);
    //     })
    //     ->where('users.id','!=',Auth::user()->id)
    //     ->select('users.*',DB::raw('MAX(chat_messages.created_at) max_created_at'))
    //     ->orderBy('max_created_at', 'desc')
    //     ->groupBy('users.id')->get();
    //     return $chats;
    // }
    
    public function getChats()
    {
        $chats = User::where(
            ['is_onboarding_complete' => 1, 'is_active' => true ])
            ->where('id', '!=', Auth::id())
            ->select('id', 'firstname', 'middlename', 'lastname', 'avatar', 'is_online')
            ->get();
        return $chats;
    }

    public function getUnreadCounts()
    {
        return ChatMessage::select('user_id', DB::raw('COUNT(*) as total'))
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->groupBy('user_id')
            ->pluck('total', 'user_id');
    }

    #[On('messageReceived')]
    public function messageReceived($senderId = null)
    {
        $this->dispatch('$refresh');
    }

    #[On('markAsRead')]
    public function markAsRead($userId = null)
    {
        ChatMessage::where('user_id', $userId)
            ->where('receiver_id', Auth::id())
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $query = $this->searchQuery;
        $users = null;
        if(!empty($query)){
            $users = User::where(['is_onboarding_complete' => 1, 'is_active' => true ])
                ->where(function ($q) use ($query) {
                    $q->where('username','LIKE','%'.$query.'%')
                    ->orWhere('firstname','LIKE','%'.$query.'%')
                    ->orWhere('middlename','LIKE','%'.$query.'%')
                    ->orWhere('lastname','LIKE','%'.$query.'%')
                    ->orWhere('email','LIKE','%'.$query.'%')
                    ->orWhere('phone','LIKE','%'.$query.'%');
                })
                ->get();
        }
        
        $chats = $this->getChats();
        $unreadCounts = $this->getUnreadCounts();
        return view('livewire.apps.chat.sidebar',compact(
           'users','chats', 'unreadCounts'
        ));
    }

}
