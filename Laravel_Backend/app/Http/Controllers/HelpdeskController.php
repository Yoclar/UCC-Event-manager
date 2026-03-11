<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class HelpdeskController extends Controller
{
    public function index()
    {
        return Chat::with('messages', 'user')
        ->where('needs_human', true)
        ->get();
    }


    public function reply(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'text' => 'required|string'
        ]);

        $chat = Chat::find($request->chat_id);

        $chat->messages()->create([
            'sender' => 'agent',
            'text' => $request->text
        ]);

        $chat->update(['needs_human' => false]);

        return ['status' => 'ok'];
    }
}
