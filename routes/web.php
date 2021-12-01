<?php

use App\Http\Controllers\ClientCalendarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Carbon\Carbon;


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
Route::post('/contractor', [LoginController::class, 'userLogin']);
// Route::view('/contractor', 'contractor');
Route::get('/contractor', function(){
    if (session()->has('username'))
        return view('contractor');
    else
        return redirect('login');
});

Route::get('/login', function(){
    if (session()->has('username'))
        return view('contractor');
    else
        return view('login');
});

Route::get('/logout', function(){
    if (session()->has('username'))
        session()->pull('username');
    
    return redirect('login');
});