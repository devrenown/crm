<?php

namespace App\Livewire\Apps\Chat;

use App\Models\User;
use Livewire\Component;
use App\Models\ChatMessage;
use Livewire\Attributes\Js;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Events\ChatMessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Event;

class Box extends Component
{
    use WithFileUploads;

    public int $authUserId;
    public $userId;
    public $messageBody;
    public $attachments = [];
    

    public function mount($userId = null)
    {
        $this->authUserId = auth()->id();
        if(!empty($userId)){
            $this->userId = $userId;
        }
    }

    public function getUser(){
        return User::findOrFail(Crypt::decrypt($this->userId));
    }

    
    public function sendMessage()
    {
        if (
            empty(trim($this->messageBody ?? '')) &&
            count($this->attachments) === 0
        ) {
            return;
        }
        
        $receiver = $this->getUser();

        $message = ChatMessage::create([
            'user_id' => auth()->user()->id,
            'from_id' => auth()->user()->id,
            'receiver_id' => $receiver->id,
            'body' => trim($this->messageBody ?? ''),
            'type' => !empty($this->attachments) ? 'file' : 'text',
            'is_read' => false,
        ]);

        if (!empty($this->attachments)) {
            foreach ($this->attachments as $file) {
                $message->addMedia($file)->toMediaCollection('chat-attachments');
            }
        }

        broadcast(new ChatMessageSent($message))->toOthers();
        $this->messageBody = '';
        $this->attachments = [];
        // ChatMessageSent::dispatch($message);
        $this->dispatch('scroll-chat');
    }

    // [On('echo:chat-message,ChatMessageSent')]
   #[On('echo-private:chat.user.{authUserId},.chat.message.sent')]
    public function refreshMessages()
    {
        $this->dispatch('scroll-chat');
    }
    
    public function fetchMessages()
    {
        $user_id = $this->getUser()->id;
    
        // mark unread messages as read
        ChatMessage::where('from_id', $user_id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true
            ]);
    
        return ChatMessage::where(function ($q) use ($user_id) {
                $q->where('from_id', auth()->id())
                  ->where('receiver_id', $user_id);
            })
            ->orWhere(function ($q) use ($user_id) {
                $q->where('from_id', $user_id)
                  ->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc');
    }

    #[Js] 
    public function scrollDown()
    {
        return '
        setTimeout(() => {
            let chatBox = document.getElementById("chatContent");
        
            if(chatBox){
                chatBox.scrollTo({
                    top: chatBox.scrollHeight,
                    behavior: "smooth"
                });
            }
        }, 200);
        ';
    }
    
    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
    
        $this->attachments = array_values($this->attachments);
    }

    public function render()
    {
        $user = null;
        $lastMessage = null;
        $messages =  null;

        if(!empty($this->userId)){
            $user = $this->getUser();
            $messages = $this->fetchMessages()->get();
            $lastMessage = $messages->last();

        }
        return view('livewire.apps.chat.box',compact(
            'user','lastMessage','messages'
        ));
    }
}
