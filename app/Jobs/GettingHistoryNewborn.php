<?php

namespace App\Jobs;

use App\Events\FinnalyProcessNewborn;
use App\Models\Newborn;
use App\Models\NewbornSync;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GettingHistoryNewborn implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lastSyncBD = NewbornSync::first();

        if (isset($lastSyncBD) && $lastSyncBD->last_bd) {
            $newborns = DB::connection('mis')
                ->table('stt_MedicalHistory')
                ->select(['MedicalHistoryID', 'FAMILY', 'Name', 'OT', 'BD', 'Sex'])
                ->where('rf_MedCardTypeID', 4)
                ->whereDate('BD', '>', $lastSyncBD->last_bd->toDateString())
                ->orderBy('MedicalHistoryID')
                ->get();

        } else {
            $newborns = DB::connection('mis')
                ->table('stt_MedicalHistory')
                ->select(['MedicalHistoryID', 'FAMILY', 'Name', 'OT', 'BD', 'Sex'])
                ->where('rf_MedCardTypeID', 4)
                ->orderBy('MedicalHistoryID')
                ->get();

        }
        $this->runTasks($newborns);

    }

    private function runTasks(Collection $newborns): void
    {
        if ($newborns->count() == 0) return;

        $tasks = [];

        Log::info('Сборка задач...');
        foreach ($newborns as $newborn) {
            $tasks[] = new CreateHistoryNewborn($newborn);
        }

        $batch = Bus::batch($tasks)
            ->then(function (Batch $batch) {
                Log::info('Синхронизация прошла успешно');

                $nowYear = Carbon::now()->year;
                $nowDate = Carbon::now()->toDateString();

                $historyBoy = Newborn::where('Sex', 1)
                    ->whereDate('BD', '=', $nowDate)
                    ->orderBy('BD')
                    ->get()->map(function ($item, $key) {
                        $item->num = $key + 1;
                        return $item;
                    });

                $historyGirl = Newborn::where('Sex', 0)
                    ->whereDate('BD', '=', $nowDate)
                    ->orderBy('BD')
                    ->get()->map(function ($item, $key) {
                        $item->num = $key + 1;
                        return $item;
                    });

                $latestTheeHistoryBoy = $historyBoy->sortByDesc('num')->values()->take(3);
                $latestTheeHistoryGirl = $historyGirl->sortByDesc('num')->values()->take(3);

                $countInDayBoy = Newborn::where('Sex', 1)
                    ->whereDate('BD', '=', $nowDate)->count();
                $countInDayGirl = Newborn::where('Sex', 0)
                    ->whereDate('BD', '=', $nowDate)->count();

                $countBoy = Newborn::where('Sex', 1)
                    ->whereYear('BD', '=', $nowYear)->count();
                $countGirl = Newborn::where('Sex', 0)
                    ->whereYear('BD', '=', $nowYear)->count();

                broadcast(new FinnalyProcessNewborn($latestTheeHistoryBoy->toArray(), $latestTheeHistoryGirl->toArray(), $countInDayBoy, $countInDayGirl, $countBoy, $countGirl));
                $lastSyncBD = NewbornSync::first();

                if (isset($lastSyncBD)) {
                    NewbornSync::first()->delete();
                }

                $lastNewborn = Newborn::whereDate('BD', '=', $nowDate)
                    ->where(function($query) {
                        $query->whereNull('FAMILY')
                            ->orWhere('FAMILY', '=', '')
                            ->orWhereNull('Name')
                            ->orWhere('Name', '=', '');
                    })
                    ->orderBy('BD', 'desc')
                    ->first();

                if (!isset($lastNewborn)) {
                    $lastNewborn = Newborn::orderBy('BD', 'desc')->first();
                }

                NewbornSync::create([
                    'last_bd' => $lastNewborn->BD,
                ]);
            })
            ->name('newborn');

        $batch->dispatch();
    }
}
