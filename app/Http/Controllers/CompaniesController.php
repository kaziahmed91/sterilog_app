<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Companies as CompaniesClass;
use Illuminate\Support\Facades\Validator;
use Exception;

class CompaniesController extends Controller
{
    public function index() {
        return view('auth.settings');
    }


    public function viewSettings() {
        // $users = UserClass::where('company_id')
    }
    
    public function register() {
        $provinces = ['AB', 'BC', 'MB','NB','NL', 'NS',' ON','PE','QC','SK','NT','NU','YU' ];
        return view('company_register', ['provinces'=> $provinces]);
    }
    
    private function companyRegisterRules () {
        return [
            'name' => 'required|unique:companies|max:255',
            'address' => 'required',
            'province' => 'required',
            'postal' => 'required',
            'key_contact' => 'required|string',
            'telephone' => 'required|digits:10',
            'email' => 'required|email'
        ];
    }

    public function registerCompany (Request $request) 
    {
        $data = $request->all();


        $validator = Validator::make($data, $this->companyRegisterRules());

        $errors = $validator->errors();

        if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
        }

        $data['public_id'] = rand(5000,10000);
        $company =  CompaniesClass::create($data);
        $company->save();

        // return back();
        // view('company_register');
    }
}
