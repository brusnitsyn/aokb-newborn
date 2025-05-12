<?php

namespace App\Jobs;

use App\Events\FinnalyProcessNewborn;
use App\Models\Newborn;
use App\Models\NewbornSync;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
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
                ->where('BD', '>', $lastSyncBD->last_bd)
                ->orderBy('MedicalHistoryID')
                ->get();

            $this->runTasks($newborns);
        } else {
            $newborns = DB::connection('mis')
                ->table('stt_MedicalHistory')
                ->select(['MedicalHistoryID', 'FAMILY', 'Name', 'OT', 'BD', 'Sex'])
                ->where('rf_MedCardTypeID', 4)
                ->orderBy('MedicalHistoryID')
                ->get();

            $this->runTasks($newborns);
        }

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
                broadcast(new FinnalyProcessNewborn());
                $lastSyncBD = NewbornSync::first();

                if (isset($lastSyncBD)) {
                    NewbornSync::first()->delete();
                }

                NewbornSync::create([
                    'last_bd' => Newborn::latest()->first()->BD,
                ]);
            })
            ->name('newborn');

        $batch->dispatch();
    }
}
