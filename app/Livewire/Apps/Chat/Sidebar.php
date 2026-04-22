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


    public function getChats()
    {
        $chats = User::where(
            ['is_onboarding_complete' => 1, 'is_active' => true ])
            ->where('id', '!=', Auth::id())
            ->select('id', 'firstname', 'middlename', 'lastname', 'avatar', 'is_online')
            ->get();
        return $chats;
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
        return view('livewire.apps.chat.sidebar',compact(
           'users','chats'
        ));
    }
}
