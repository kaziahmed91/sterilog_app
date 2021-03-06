<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Company;
use App\SoftUser;

class Sterilizer extends Model
{

    protected $table = 'sterilizers';
    protected $guarded = ['company_id'];
    protected $fillable = 
        ['company_id','sterilizer_name','serial','added_by', 
        'created_at','manufacturer', 'date_deleted', 'active', 'cycle_number'];
    
    // use SoftDeletes;
    public $timestamps = false;


    public function company() {
        return $this->hasOne(Company::class, 'company_id', 'company_id');
    }

    public function softUser() {
        return $this->hasOne(SoftUser::class, 'added_by', 'id');
    }

    
}
