<?php

namespace App\Http\Controllers;

use App\Models\Newborn;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class NewbornController extends Controller
{
    public function index()
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
