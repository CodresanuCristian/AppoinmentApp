<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Client;


class ClientCalendarController extends Controller
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

        return view('client')->with(['calendar' => $calendar]);

    }


    public function NewAppointment(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|digits:10',
            'contractor' => 'required',
            'date' => 'required',
            'hour' => 'required',
            'minute' => 'required',
            'services' => 'required'
        ]);

        $data = new Client;

        $data->name = $request['name'];
        $data->phone = $request['phone'];
        $data->contractor = $request['contractor'];
        $data->services = $request['services'];
        $data->date = $request['date'];
        $data->start_hour = $request['hour'];
        $data->start_minute = $request['minute'];
        $data->finish_hour = '2';
        $data->finish_minute = '1';

        $data->save();

        if ($request['page'] == 'client')
            return redirect('/');
        else
            return redirect('/contractor');
    }
    

}
