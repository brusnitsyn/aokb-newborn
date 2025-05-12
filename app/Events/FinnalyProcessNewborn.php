<?php

namespace App\Events;

use App\Models\Newborn;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Laravel\Reverb\Loggers\Log;

class FinnalyProcessNewborn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $latestTheeHistoryBoy;
    private $latestTheeHistoryGirl;
    private int $countInDayBoy;
    private int $countInDayGirl;
    private int $countBoy;
    private int $countGirl;

    /**
     * Create a new event instance.
     */
    public function __construct(array $latestTheeHistoryBoy, array $latestTheeHistoryGirl, int $countInDayBoy,
                                int $countInDayGirl, int $countBoy, int $countGirl)
    {
        $this->latestTheeHistoryBoy = $latestTheeHistoryBoy;
        $this->latestTheeHistoryGirl = $latestTheeHistoryGirl;
        $this->countInDayBoy = $countInDayBoy;
        $this->countInDayGirl = $countInDayGirl;
        $this->countBoy = $countBoy;
        $this->countGirl = $countGirl;
    }

    /**
     * Имя транслируемого события.
     */
    public function broadcastAs(): string
    {
        return 'aokb.newborn.finally';
    }

    public function broadcastWith(): array
    {
        return [
            'latestTheeHistoryBoy' => $this->latestTheeHistoryBoy,
            'latestTheeHistoryGirl' => $this->latestTheeHistoryGirl,
            'countInDayBoy' => $this->countInDayBoy,
            'countInDayGirl' => $this->countInDayGirl,
            'countBoy' => $this->countBoy,
            'countGirl' => $this->countGirl,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('aokb.newborn.finally'),
        ];
    }
}
