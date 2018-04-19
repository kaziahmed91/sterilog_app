<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SoftUser extends Model
{
    protected $table='soft_user';
    protected $fillable = [ 'user_name', 'first_name', 'last_name', 'password', 'company_id'];


    public function company() {
        return $this->hasOne(Company::class, 'company_id','company_id');
    }
    public function cycles() {
        return $this->hasMany(Cycles::class, 'id', 'id');
    }
    public function sporeTest() {
        return $this->hasMany(SporeTest::class, 'id', 'user_id');
    }




}
