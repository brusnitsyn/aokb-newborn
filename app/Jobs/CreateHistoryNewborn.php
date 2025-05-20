<?php

namespace App\Jobs;

use App\Models\Newborn;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateHistoryNewborn implements ShouldQueue
{
    use Batchable, Queueable;

    private object $newborn;

    /**
     * Create a new job instance.
     */
    public function __construct(object $newborn)
    {
        $this->newborn = $newborn;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Newborn::updateOrCreate(
            [
                'MedicalHistoryID' => $this->newborn->MedicalHistoryID
            ],
            [
                'MedicalHistoryID' => $this->newborn->MedicalHistoryID,
                'FAMILY' => $this->newborn->FAMILY,
                'Name' => $this->newborn->Name,
                'OT' => $this->newborn->OT,
                'BD' => $this->newborn->BD,
                'Sex' => $this->newborn->Sex,
                'rf_MotherMHID' => $this->newborn->rf_MotherMHID,
            ]
        );
    }
}
