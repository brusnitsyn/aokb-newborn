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
                return [
                    'num' => $item->num = $key + 1,
                    'Name' => $item->Name = Str::title($item->Name),
                    'FAMILY' => $item->FAMILY = Str::title($item->FAMILY),
                    'OT' => $item->OT = Str::title($item->OT),
                    'date' => Carbon::parse($item->BD)->diffForHumans(),
                ];
            });

        $historyGirl = Newborn::where('Sex', 0)
            ->whereDate('BD', '=', $nowDate)
            ->orderBy('BD')
            ->get()->map(function ($item, $key) {
                return [
                    'num' => $item->num = $key + 1,
                    'Name' => $item->Name = Str::title($item->Name),
                    'FAMILY' => $item->FAMILY = Str::title($item->FAMILY),
                    'OT' => $item->OT = Str::title($item->OT),
                    'date' => Carbon::parse($item->BD)->diffForHumans(),
                ];
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
