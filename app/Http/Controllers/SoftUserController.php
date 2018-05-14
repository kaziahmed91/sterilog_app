<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\SoftUser as SoftUserModel;
use Session;

class SoftUserController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');

    }

    public function index()
    {
        return view('auth.softUserLogin');
    }

    public function login(Request $request)
    {
        $data = $request->all(); 

        // $validator = Validator::make($data, [
        //     // 'user_name' => 'required',
        //     'password' => 'required|numeric|digits_between:4,6'
        // ]);
        
        // $errors = $validator->errors()->toArray();

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        $currentlyLoggedIn = $request->session()->get('softUser_userName');

        // if (is_null($currentlyLoggedIn) || $currentlyLoggedIn === '') {
        $softUser = SoftUserModel::where('company_id', \Auth::user()->company_id)
            ->where('password', $data['password'])->first();

        if (!$softUser) {
            return response()->json(['response' => 'error', 'message' => 'Invalid Pin!'], 401);
        }
        $softUser_name = $softUser->first_name . ' ' . $softUser->last_name;
        $request->session()->put([
            'softUser_userName' => $softUser->user_name,
            'softUser_fullName' => $softUser_name,
            'softUser_lastActive' => time(),
            'softUser_startTime' => time()
        ]);

        return response()->json(['response' => 'success'], 200);

            // Session::flash('success', 'User '.$softUser_name.' is logged in!');
            // return redirect()->back();
        // }
    }

    public function logout(Request $request)
    {
        $currentlyLoggedIn = $request->session()->get('softUser_userName');

        if (!is_null($currentlyLoggedIn) || $currentlyLoggedIn !== '') {
            $request->session()->forget(['softUser_fullName', 'softUser_userName', 'softUser_startTime']);
            Session::flash('success', 'User has been logged out!');
            return redirect()->back();
        }
        Session::flash('error', 'User could not be logged out!');
        return redirect()->back();
    }
}
