<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class ContractorController extends Controller
{
    public function CreateCalendar(Request $request, $getMonth, $getYear)
    {
        $changed_date = Carbon::createFromDate($getYear, $getMonth, '1');
        $calendar = [
            'year' => $changed_date->format('Y'),
            'month' => $changed_date->format('F'),
            'monthDigit' => $changed_date->month,
            'today' => Carbon::now()->day,
            'daysInMonth' => $changed_date->daysInMonth,
            'skipDays' => $changed_date->dayOfWeek,
            'dayTiles' => $changed_date->daysInMonth + $changed_date->dayOfWeek
        ];

        return view('contractor')->with(['calendar' => $calendar]);

    }
}
