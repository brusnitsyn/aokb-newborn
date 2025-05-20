<?php

namespace App\Console\Commands;

use App\Jobs\CreateHistoryNewborn;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetChildrenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mis:get-children';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получить карты с признаком rf_MedCardType = 4 и год = текущий';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $year = Carbon::now()->year;

        $newborns = DB::connection('mis')
            ->table('stt_MedicalHistory as kind')
            ->select(['kind.MedicalHistoryID', 'mother.FAMILY', 'mother.Name', 'mother.OT', 'kind.BD', 'kind.Sex', 'kind.rf_MotherMHID'])
            ->join('stt_MedicalHistory as mother', 'kind.rf_MotherMHID', '=', 'mother.MedicalHistoryID')
            ->where('kind.rf_MedCardTypeID', '=', 4)
            ->whereYear('kind.BD', '=', $year)
            ->get();

        if ($newborns->count() == 0) return;

        $tasks = [];

        foreach ($newborns as $newborn) {
            $tasks[] = new CreateHistoryNewborn($newborn);
        }

        Bus::batch($tasks)
            ->dispatch();
    }
}
