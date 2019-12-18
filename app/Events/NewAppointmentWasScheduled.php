<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewAppointmentWasScheduled
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $proposal, $appointmentData, $services;

    public function __construct($proposal, $appointmentData, $services)
    {
        $this->proposal = $proposal;
        $this->appointmentData = $appointmentData;
        $this->services = $services;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
