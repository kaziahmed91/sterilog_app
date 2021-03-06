<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SoftUser;
use App\Sterilizer;

class SporeTest extends Model
{
    protected $table = 'spore_test';
    protected $fillable = ['entry_operator_id','company_id', 'test_sterile', 'control_sterile',
       'initial_comments','additional_comments','entry_cycle_number','sterilizer_id' ,'lot_number', 'entry_at' ];
    public $timestamps = false;


    public function entryUser()
    {
        return $this->hasOne(SoftUser::class, 'id', 'entry_operator_id');
    }
    
    public function removalUser()
    {
        return $this->hasOne(SoftUser::class, 'id', 'removal_operator_id');
    }

    public function sterilizer() 
    {
        return $this->hasOne(Sterilizer::class, 'id', 'sterilizer_id');
    }
}
