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
    public function __construct()
    {
        $nowYear = Carbon::now()->year;
        $nowDate = Carbon::now()->toDateString();

        $historyBoy = Newborn::where('Sex', 1)
            ->where('BD', '>', $nowDate)
            ->orderBy('BD')
            ->get()->map(function ($item, $key) {
                $item->num = $key + 1;
                return $item;
            });
        $historyGirl = Newborn::where('Sex', 0)
            ->where('BD', '>', $nowDate)
            ->orderBy('BD')
            ->get()->map(function ($item, $key) {
                $item->num = $key + 1;
                return $item;
            });

        $latestTheeHistoryBoy = $historyBoy->sortByDesc('num')->values()->take(3);
        $latestTheeHistoryGirl = $historyGirl->sortByDesc('num')->values()->take(3);

        $countInDayBoy = Newborn::where('Sex', 1)
            ->where('BD', '>', $nowDate)->count();
        $countInDayGirl = Newborn::where('Sex', 0)
            ->where('BD', '>', $nowDate)->count();

        $countBoy = Newborn::where('Sex', 1)
            ->whereYear('BD', '=', $nowYear)->count();
        $countGirl = Newborn::where('Sex', 0)
            ->whereYear('BD', '=', $nowYear)->count();

        return [
            $this->latestTheeHistoryBoy = $latestTheeHistoryBoy,
            $this->latestTheeHistoryGirl = $latestTheeHistoryGirl,
            $this->countInDayBoy = $countInDayBoy,
            $this->countInDayGirl = $countInDayGirl,
            $this->countBoy = $countBoy,
            $this->countGirl = $countGirl,
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
            new Channel('aokb-newborn'),
        ];
    }
}
