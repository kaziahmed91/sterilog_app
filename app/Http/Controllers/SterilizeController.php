<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies as CompaniesModel;
use App\User as UserModel;
use App\Sterilizer as SterilizeModel;
use Exception;

class SterilizeController extends Controller
{
    public function __construct() {
        
    }

    public function index () {

        $sterilizers = SterilizeModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();
        return view('auth.sterilize', ['equiptment' => $sterilizers]);
    }

    public function logPost(Request $request ) {
        
        return view('auth.sterilizeLog');
    } 

    public function viewLog ()
    {
        return view('auth.sterilizeLog');
    }

    public function sterilize() 
    {
        
    }



}
