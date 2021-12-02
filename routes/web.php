<?php


use Carbon\Carbon;
use App\Http\Controllers\ClientCalendarController;
use App\Http\Controllers\ContractorController;
use App\Http\Controllers\LoginController;
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



Route::get('/contractor', function(){
    return redirect('contractor/'.Carbon::now()->month.'-'.Carbon::now()->year);
})->middleware('protectedPage');


Route::get('/contractor/{month}-{year}', [ContractorController::class, 'CreateCalendar']) -> middleware('protectedPage');
Route::post('/contractor', [LoginController::class, 'userLogin']);



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

