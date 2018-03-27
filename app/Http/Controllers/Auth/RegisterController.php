<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Companies as CompaniesModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Rules\ValidPublicId;
use Exception;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'public_id' => ['required','string', new ValidPublicId ],
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   

        try {
            $company = CompaniesModel::where('public_id', $data['public_id'])->first();
            if ($company)
                $company_id = $company->id;
        } catch (Exception $e) {
            error_log($e->getMessage() );
            error_log($e->getLine());
        }

        $data['company_id'] = $company_id;
        $userObj = User::create([
            'name' => $data['name'],
            'email' => $data['email'],  
            'company_id' => $data['company_id'],
            'password' => bcrypt($data['password']),
        ]);

        return $userObj;
    }
}
