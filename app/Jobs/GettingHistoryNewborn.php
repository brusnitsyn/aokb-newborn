<?php

namespace App\Jobs;

use App\Models\Newborn;
use App\Models\NewbornSync;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;

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

        if ($lastSyncBD && $lastSyncBD->last_bd) {
            $newborns = DB::connection('mis')
                ->table('stt_MedicalHistory')
                ->select(['MedicalHistoryID', 'FAMILY', 'Name', 'OT', 'BD', 'Sex'])
                ->where('BD', '>', $lastSyncBD)
                ->orderBy('BD', 'desc')
                ->get();
        } else {
            $newborns = DB::connection('mis')
                ->table('stt_MedicalHistory')
                ->select(['MedicalHistoryID', 'FAMILY', 'Name', 'OT', 'BD', 'Sex'])
                ->orderBy('BD', 'desc')
                ->get();
        }

        foreach ($newborns as $newborn) {
            Newborn::create($newborn->toArray());
        }

        $lastSyncBD->update([
            'last_bd' => Newborn::first()->BD
        ]);
    }
}
