<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Cycles;
use App\SporeTest;
use App\Companies;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'company_id', 'type_5'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table = 'users';


    public function company() {
        return $this->belongsTo(Companies::class, 'company_id','id');
    }

    public function cycles() {
        return $this->hasMany(Cycles::class, 'user_id', 'user_id');
    }
    public function sporeTest() {
        return $this->hasMany(SporeTest::class, 'user_id', 'user_id');
    }

    public function getUserCompany() {
        return $this->company();
    }
}
