<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Company;
use App\User;

class Sterilizer extends Model
{

    protected $table = 'sterilizers';
    protected $guarded = ['company_id'];
    protected $fillable = 
        ['company_id','sterilizer_name','serial','added_by', 
        'date_added','manufacturer', 'date_deleted', 'active'];
    
    // use SoftDeletes;
    public $timestamps = false;


    public function company() {
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }

    public function user() {
        return $this->hasMany('User::class', 'added_by', 'user_id');
    }

    
}
