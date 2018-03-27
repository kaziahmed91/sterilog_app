<?php

namespace App\Rules;
use Illuminate\Contracts\Validation\Rule;
use App\Companies as CompaniesModel;

class ValidPublicId implements Rule
{

    public function __construct() 
    {

    }

    public function passes($attribute, $value) 
    {
        try {
            $public_id = CompaniesModel::where('public_id', $value)->first();
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            throw $e;
        }
        $pass = $public_id ? true : false;
        return $pass;
    }

    public function message()
    {
        return 'The Companys  Id is invalid';
    }
}
