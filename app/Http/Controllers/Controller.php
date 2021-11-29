<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function carbon(){
        $year_now = Carbon::now()->year;
        $month_now = Carbon::now()->month;
        $day_now = Carbon::now()->day;
        $days_in_month = Carbon::createFromDate($year_now, $month_now, $day_now)->daysInMonth;
   
        $calendar = ([
            'year' => $year_now, 
            'month' => $month_now,
            'day' => $day_now,
            'days_in_month' => $days_in_month,
        ]);

        return $calendar;
    }
}
