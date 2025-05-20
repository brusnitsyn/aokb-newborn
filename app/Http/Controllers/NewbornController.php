<?php

namespace App\Http\Controllers;

use App\Models\Newborn;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NewbornController extends Controller
{
    public function index()
    {
        $nowYear = Carbon::now()->year;
        $nowDate = Carbon::now()->toDateString();

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

        return Inertia::render('Newborn/Index', [
            'latestTheeHistoryBoy' => $latestTheeHistoryBoy,
            'latestTheeHistoryGirl' => $latestTheeHistoryGirl,
            'countInDayBoy' => $countInDayBoy,
            'countInDayGirl' => $countInDayGirl,
            'countBoy' => $countBoy,
            'countGirl' => $countGirl,
        ]);
    }
}
