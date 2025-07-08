<?php

namespace App\Events;

use App\Models\Alert;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAlertCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alert;

    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    public function broadcastOn()
    {
        return new Channel('alerts');
    }

    public function broadcastWith()
    {
        return [
            'alert' => $this->alert,
            'type' => 'new_alert'
        ];
    }
}

class AlertStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $alert;

    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    public function broadcastOn()
    {
        return new Channel('alerts');
    }

    public function broadcastWith()
    {
        return [
            'alert' => $this->alert,
            'type' => 'status_updated'
        ];
    }
}
