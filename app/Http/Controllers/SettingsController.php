<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as UserClass;
use App\Sterilizer as SterilizerClass;
use Carbon\Carbon;

class SettingsController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index () {
        $company_id = \Auth::user()->company_id;
        $users = UserClass::where('company_id', $company_id)->whereNull('date_deleted')->get();
        $equiptments = SterilizerClass::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray(); 

        return view('auth.settings', ['users' => $users, 'equiptments' => $equiptments ]);
    }

    
    public function addSterilizer (Request $request) 
    {
        $data = $request->all();
        $company_id = \Auth::user()->company_id;

        $sterilizer = SterilizerClass::create([
            'company_id' => $company_id,
            'sterilizer_name' => $data['sterilizer_name'], 
            'manufacturer' => $data['manufacturer'],
            'serial' => $data['serial'],
            'added_by' => \Auth::user()->id , 
            'date_added' => Carbon::now()
        ]);
        
        if (!$sterilizer)
            throw new Exception("Equiptment Could not be added", 400);

        $this->index();
    }
}
