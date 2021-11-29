<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Reflector;

class ClientCalendarController extends Controller
{
    public function CreateCalendar(Request $request, $getMonth)
    {
        $date_now = Carbon::now();

        if ($getMonth == 'home')
            $getMonth = $date_now->month;

        $getMonth = (int)$getMonth;
        $changed_date = Carbon::createFromDate($date_now->year, $getMonth, '1');
        
        $calendar = [
            'year' => $changed_date->format("Y"),
            'month' => $changed_date->format("F"),
            'monthNow' => $changed_date->month,
            'today' => $date_now->day,
            'daysInMonth' => $changed_date->daysInMonth,
            'skipDays' => $changed_date->dayOfWeek,
            'dayBoxes' => $changed_date->daysInMonth + $changed_date->dayOfWeek,
        ];
        
        return view('client')->with(['calendar' => $calendar]);
    }


    

}
