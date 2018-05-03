<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\SoftUser as SoftUserModel;
use Session;

class SoftUserController extends Controller
{

    public function index() 
    {
        return view('auth.softUserLogin');
    }
    
    public function login (Request $request)
    {
        $data =$request->all(); 

        $validator = Validator::make($data, [
            'user_name' => 'required',
            'password' => 'required|numeric|digits_between:4,6'
        ]);
        
        $errors = $validator->errors()->toArray();

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $currentlyLoggedIn = $request->session()->get('softUser_userName');

        if (is_null($currentlyLoggedIn) || $currentlyLoggedIn === '') {
            $softUser = SoftUserModel::where('user_name', $data['user_name'])
                ->where('password', $data['password'])->first();
            
            if (!$softUser) {
                Session::flash('error', 'Incorrect User credentials! Please try again.');
                return redirect()->back();
            }
            $softUser_name = $softUser->first_name.' '.$softUser->last_name;
            $request->session()->put([
                'softUser_userName' => $data['user_name'], 
                'softUser_fullName' => $softUser_name,
                'softUser_lastActive' => time(),
                'softUser_startTime' => time()
            ]); 
            
            Session::flash('success', 'User '.$softUser_name.' is logged in!');
            return redirect()->back();
        }
    }

    public function logout (Request $request)
    {
        $currentlyLoggedIn = $request->session()->get('softUser_userName');

        if (!is_null($currentlyLoggedIn) || $currentlyLoggedIn !== '' )
        {
            $request->session()->forget(['softUser_fullName', 'softUser_userName', 'softUser_startTime']);
            Session::flash('success', 'User has been logged out!');
            return redirect()->back();
        }
        Session::flash('error', 'User could not be logged out!');
        return redirect()->back();
    }
}
