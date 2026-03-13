<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Message;



class Chat extends Model
{
    protected $fillable = ['user_id','needs_human', 'status'];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
