<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;

class ChatController extends Controller
{
    public function chat(Request $request)
    { 
        $user = auth()->user();

        $chat = Chat::firstOrCreate(
            ['user_id' => $user->id],
            ['needs_human' => false]
        );
        

        $chat->messages()->create([
            'sender' => 'user',
            'text' => $request->message
        ]);

        $msg = strtolower($request->message);
        $reply = "Sorry, I don't understand. Please rephrase your question.";

        if (str_contains($msg,'hello') || str_contains($msg, 'hi')) {
            $reply = 'Nice to meet you, how can I help you today?';
        } 
        elseif (str_contains($msg, 'help')) {
            $reply = 'Could you clarify the question?';
        }
        elseif (str_contains($msg, 'agent') || str_contains($msg, 'human') || str_contains($msg, 'operator') || str_contains($msg, 'support')) {
            $chat->update(['needs_human' => true]);   
            $reply = "Alright, I'll put you through to one of our agents. Please wait ...";
        }
        elseif (str_contains($msg, 'create') || str_contains($msg, 'add') || str_contains($msg, 'new')) {
            $reply = 'To create a new event, go back and click the "New Event" button.';
        }
        elseif (str_contains($msg, 'update') || str_contains($msg, 'modify')) {
            $reply = 'You can modify events by clicking the "Modify" button and saving your changes.';
        }
        elseif (str_contains($msg, 'delete') || str_contains($msg, 'remove')) {
            $reply = 'To delete an event, click "Delete Event" and confirm in the pop-up window.';
        }
        


        $chat->messages()->create([
            'sender' => 'bot',
            'text' => $reply
        ]);

        return ['reply' => $reply];
    }
}
