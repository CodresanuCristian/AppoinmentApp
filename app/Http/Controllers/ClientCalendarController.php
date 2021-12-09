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

        $clock = Carbon::createFromTime($request['hour'], $request['minute'], '0');
        $services = explode(',', $request['services']);
        $service_time = 0;

        for ($i=0; $i<count($services); $i++){
            if ($services[$i] == 'Service 1') $service_time = $service_time + 45;
            if ($services[$i] == ' Service 2') $service_time = $service_time + 35;
            if ($services[$i] == ' Service 3') $service_time = $service_time + 30;
            if ($services[$i] == ' Service 4') $service_time = $service_time + 60;
            if ($services[$i] == ' Service 5') $service_time = $service_time + 20;
            if ($services[$i] == ' Service 6') $service_time = $service_time + 45;
        }
        $clock = $clock->addMinutes($service_time);
        
        $data->finish_hour = $clock->hour;
        $data->finish_minute = $clock->format('i');


        $data->save();

        if ($request['page'] == 'client')
            return redirect('/');
        else
            return redirect('/contractor');
    }
    



    public function GetSchedule(Request $request)
    {
        $db_schedule = new Client;
        $db_schedule = $db_schedule->where('date', $request['date'])->orderBy('start_hour')->get();

        return response()->json(['db'=>$db_schedule]);
    }

}
