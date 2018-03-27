<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Users;
use App\Sterilizer;

class Companies extends Model
{
    protected $table= 'Companies';
    protected $fillable = array('name', 'address', 'province','telephone','public_id' ,'postal','email','key_contact');



    public function users() {
        return $this->hasMany(Users::class, 'company_id', 'company_id');
    }
    public function sterilizers() {
        return $this->hasMany(Sterilizer::class, 'company_id', 'company_id');
    }
}
