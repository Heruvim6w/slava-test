<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RowCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $row;

    public function __construct($row)
    {
        $this->row = $row;
    }
}
