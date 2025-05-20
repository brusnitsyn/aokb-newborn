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
use Illuminate\Support\Str;

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
            $date = Carbon::parse($lastSyncBD->last_bd)->format('Ymd H:i:s');
            $newborns = DB::connection('mis')
                ->table('stt_MedicalHistory as kind')
                ->select(['kind.MedicalHistoryID', 'mother.FAMILY', 'mother.Name', 'mother.OT', 'kind.BD', 'kind.Sex', 'kind.rf_MotherMHID'])
                ->join('stt_MedicalHistory as mother', 'kind.rf_MotherMHID', '=', 'mother.MedicalHistoryID')
                ->where('kind.BD', '>', $date)
                ->get();

        } else {
            $nowDate = Carbon::now()->format('Ymd');
            $newborns = DB::connection('mis')
                ->table('stt_MedicalHistory as kind')
                ->select(['kind.MedicalHistoryID', 'mother.FAMILY', 'mother.Name', 'mother.OT', 'kind.BD', 'kind.Sex', 'kind.rf_MotherMHID'])
                ->join('stt_MedicalHistory as mother', 'kind.rf_MotherMHID', '=', 'mother.MedicalHistoryID')
                ->where('kind.BD', '>', $nowDate)
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
                $nowDate = Carbon::now()->format('Ymd');

                $historyBoy = Newborn::where('Sex', 1)
                    ->whereDate('BD', '=', $nowDate)
                    ->orderBy('BD')
                    ->get()->map(function ($item, $key) {
                        $item->num = $key + 1;
                        $item->Name = Str::title($item->Name);
                        $item->FAMILY = Str::title($item->FAMILY);
                        $item->OT = Str::title($item->OT);
                        return $item;
                    });

                $historyGirl = Newborn::where('Sex', 0)
                    ->whereDate('BD', '=', $nowDate)
                    ->orderBy('BD')
                    ->get()->map(function ($item, $key) {
                        $item->num = $key + 1;
                        $item->Name = Str::title($item->Name);
                        $item->FAMILY = Str::title($item->FAMILY);
                        $item->OT = Str::title($item->OT);
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

                $lastNewborn = Newborn::where('BD', '>=', $nowDate)
                    ->where(function($query) {
                        $query->whereNull('rf_MotherMHID')
                            ->orWhere('rf_MotherMHID', '=', 0);
                    })
                    ->first();

                if (!isset($lastNewborn)) {
                    $lastNewborn = Newborn::latest('BD')->first();
                }

                NewbornSync::create([
                    'last_bd' => $lastNewborn->BD,
                ]);
            })
            ->name('newborn');

        $batch->dispatch();
    }
}
