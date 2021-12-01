<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contractor;

class LoginController extends Controller
{
    function userLogin(Request $request)
    {
        $input_data = $request->input();
        $db_data = new Contractor;
        $find = false;
        
        foreach ($db_data->get() as $contractor)
            if (($contractor->username == $input_data['username']) && ($contractor->password == $input_data['password'])){
                $request->session()->put('username', $input_data['username']);
                $find = true;
            }

        if ($find == true)
            return view('contractor');
        else
            return redirect('login');
    }
}
