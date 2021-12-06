<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contractor;
use App\Models\ContractorDetails;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ContractorController extends Controller
{


    // CALENDAR ===================================================================================================================
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

        
        $db_appointment = new Client;
        $db_appointment = $db_appointment->where('contractor', session('username'))->get();


        return view('contractor')->with(['calendar' => $calendar, 'db_appointment' => $db_appointment]);

    }


    // CONTRACTOR DETAILS =========================================================================================================
    public function AddNewContractor(Request $request)
    {
        $request->validate([
            'newcontractor' => 'required',
            'newcontractorpass' => 'required'
        ]);

        $data = new Contractor;
        $data->username = $request['newcontractor'];
        $data->password = $request['newcontractorpass'];
        $data->save();

        return back();
    }    


    public function AddDaysOff(Request $request)
    {
        $request->validate([
            'adddaysoff' => 'required'
        ]);

        $data = new ContractorDetails;
        $data->name = session('username');
        $data->days_off = $request['adddaysoff'];
        $data->start_holiday = '';
        $data->finish_holiday = '';
        $data->save();

        return back();
    }


    public function AddHoliday(Request $request)
    {
        $request->validate([
            'startholiday' => 'required',
            'finishholiday' => 'required'
        ]);

        $date = new ContractorDetails;
        $date->name = session('username');
        $date->days_off = '';
        $date->start_holiday = $request['startholiday'];
        $date->finish_holiday = $request['finishholiday'];
        $date->save();

        return back();
    }


    public function ShowContractorDetails()
    {
        $contractor_details = new ContractorDetails;
        $contractor_details = $contractor_details->where('name', session('username'))->get(); 

        $contractors = new Contractor;
        $contractors = $contractors->get();

        return response()->json(['contractor_details' => $contractor_details, 'contractor' => $contractors]);
    }


    public function DeleteContractor(Request $request)
    {
        $db_contractors = new Contractor;
        $db_contractor_details = new ContractorDetails;
        $db_clients = new Client;

        $db_contractors->where('username', $request['deletecontractor'])->delete();
        $db_contractor_details->where('name', $request['deletecontractor'])->delete();
        $db_clients->where('contractor', $request['deletecontractor'])->delete();

        return back();
    }


    public function DeleteDaysOff(Request $request)
    {
        $db_contractor_details = new ContractorDetails;
        $db_contractor_details->where('days_off', $request['deletedaysoff'])->delete();
        echo ($request['deletedaysoff']);

        return back();
    }


    public function DeleteHoliday(Request $request)
    {
        $db_contractor_details = new ContractorDetails;
        $db_contractor_details->where('start_holiday', $request['deleteholiday'])->delete();

        return back();
    }


/*
    public function ChangeDate(Request $request)
    {
        return response()->json(array('newDate'=> $request['date_filter']));
    }



    public function DeleteAppointment(Request $request)
    {
        $db_appointment = new Client;
        $db_appointment = $db_appointment->where('id', $request['id'])->delete();

        return redirect('/contractor');
    }*/
}
