<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as UserClass;
use App\Sterilizer as SterilizerClass;
use App\Cleaners as CleanersClass;
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
        $cleaners = CleanersClass::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray(); 

        return view('auth.settings', ['users' => $users, 'equiptments' => $equiptments, 'cleaners' => $cleaners ]);
    }

    public function getCompanyView ()
    {
        return view('auth.settings.company', ['route' => 'company']);
    }

    public function getCleanersView ()
    {
        $company_id = \Auth::user()->company_id;
        $cleaners = CleanersClass::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray(); 
        return view('auth.settings.cleaners', ['route' => 'cleaners', 'cleaners' => $cleaners ]);
    }

    public function getUserView () 
    {
        $company_id = \Auth::user()->company_id;
        $users = UserClass::where('company_id', $company_id)->whereNull('date_deleted')->get();

        return view('auth.settings.user', ['users' => $users, 'route' => 'user']);

    }

    public function getEquiptmentView ()
    {           
        $company_id = \Auth::user()->company_id;
        $equiptments = SterilizerClass::where('company_id', $company_id)->whereNull('date_deleted')->get()->toArray(); 
        return view('auth.settings.equiptment', ['route' => 'equiptment', 'equiptments' => $equiptments]);
    }
    public function addCleaner (Request $request)
    {  
        $company_id = \Auth::user()->company_id;
        $data = $request->all();
        $cleaner = CleanersClass::create([
            'name' => $data['cleaner_name'], 
            'company_id' => $company_id, 
            'added_by' => \Auth::user()->id
        ]);
        
        $this->index();

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

        $this->back();
    }
}
