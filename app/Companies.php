<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Users;
use App\SoftUsers;
use App\Sterilizer;

class Companies extends Model
{
    protected $table= 'companies';
    protected $fillable = array('name', 'address', 'province','telephone','public_id' ,'postal','email','key_contact');



    public function users() {
        return $this->hasOne(Users::class, 'id', 'company_id');
    }
    public function softUsers() {
        return $this->hasMany(SoftUsers::class, 'id', 'company_id');
    }

    public function sterilizers() {
        return $this->hasMany(Sterilizer::class, 'id', 'company_id');
    }
}
