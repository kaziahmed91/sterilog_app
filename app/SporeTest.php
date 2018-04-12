<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Sterilizer;

class SporeTest extends Model
{
    protected $table = 'spore_test';
    protected $fillable = ['entry_operator_id','company_id', 'test_sterile', 'control_sterile',
       'initial_comments', 'entry_cycle_number','sterilizer_id' ,'lot_number', 'entry_at' ];
    public $timestamps = false;


    public function entryUser()
    {
        return $this->hasMany(User::class, 'id', 'entry_operator_id');
    }
    
    public function removalUser()
    {
        return $this->hasMany(User::class, 'id', 'removal_operator_id');
    }

    public function sterilizer() 
    {
        return $this->hasOne(Sterilizer::class, 'id', 'sterilizer_id');
    }
}
