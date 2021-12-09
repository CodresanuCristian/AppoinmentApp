<?php


use Carbon\Carbon;
use App\Http\Controllers\ClientCalendarController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\LoginController;
use App\Models\Contractor;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/'.Carbon::now()->month.'-'.Carbon::now()->year);
});

Route::get('/{month}-{year}', [ClientCalendarController::class, 'CreateCalendar']);
Route::post('/appointment', [ClientCalendarController::class, 'NewAppointment']);
Route::get('/getSchedule', [ClientCalendarController::class, 'GetSchedule']);




Route::get('/contractor', function(){
    return redirect('contractor/'.Carbon::now()->month.'-'.Carbon::now()->year);
})->middleware('protectedPage');

Route::get('/contractor/{month}-{year}', [ContractorController::class, 'CreateCalendar']) -> middleware('protectedPage');
Route::post('/contractor', [LoginController::class, 'UserLogin']);
Route::post('/addnewcontractor', [ContractorController::class, 'AddNewContractor']) -> middleware('protectedPage');
Route::post('/adddaysoff', [ContractorController::class, 'AddDaysOff']) -> middleware('protectedPage');
Route::post('/addholiday', [ContractorController::class, 'AddHoliday']) -> middleware('protectedPage');
Route::get('/showcontractordetails', [ContractorController::class, 'ShowContractorDetails']) -> middleware('protectedPage');
Route::get('/deletecontractor', [ContractorController::class, 'DeleteContractor']) -> middleware('protectedPage');
Route::get('/deletedaysoff', [ContractorController::class, 'DeleteDaysOff']) -> middleware('protectedPage');
Route::get('/deleteholiday', [ContractorController::class, 'DeleteHoliday']) -> middleware('protectedPage');
Route::get('/showapplist', [ContractorController::class, 'ShowAppList']) -> middleware('protectedPage');
Route::get('/deleteappointment', [ContractorController::class, 'DeleteAppointment']) -> middleware('protectedPage');
Route::post('/editappointment', [ContractorController::class, 'EditAppointment']) -> middleware('protectedPage');


Route::get('/login', function(){
    if (session()->has('username'))
        return redirect('contractor/'.Carbon::now()->month.'-'.Carbon::now()->year);
    else
        return view('login');
});




Route::get('/logout', function(){
    if (session()->has('username'))
        session()->pull('username');
    
    return redirect('/login');
});