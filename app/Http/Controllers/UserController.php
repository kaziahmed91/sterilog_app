<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User as UserModel;

class UserController extends Controller
{

    public function __construct() {
        $company_id = \Auth::user()->company_id();
    }


    public function login() 
    {
        return view('login');
    }


    public function getAllUsers() 
    {
        $users = UserModel::where('company_id', $company_id)->whereNull('date_deleted');
    }


    public function addNewUser(Request $request)
    {
        $data = $request->all();
    }
}
