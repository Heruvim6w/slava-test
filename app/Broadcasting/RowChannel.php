<?php

namespace App\Broadcasting;

use Illuminate\Support\Facades\Auth;
use Illuminate\Broadcasting\PresenceChannel;

class RowChannel
{
    public function join($user)
    {
        if (Auth::check()) {
            return new PresenceChannel('rows');
        }
    }

    public function leave($user)
    {
        //
    }
}
