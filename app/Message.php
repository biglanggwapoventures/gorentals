<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sent_from',
        'sent_to',
        'message'
    ];
    
}
