<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chat;

class Message extends Model
{
    protected $fillable = ['chat_id', 'sender','text'];
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
