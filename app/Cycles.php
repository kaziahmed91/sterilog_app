<?php

namespace App;
use App\Sterilizer;
use App\Cleaners; 
use App\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Undocumented class
 */
class Cycles extends Model
{   

    protected $fillable = 
        ['company_id','sterilizer_id','user_id','units_printed', 
        'cycle_number','comment', 'cleaner_id'];
    /**
     * Undocumented function
     *
     * @return void
     */
    public function sterilizer () {
        return $this->hasOne(Sterilizer::class, 'id', 'sterilizer_id');
    }

    public function cleaners() {
        return $this->hasOne(Cleaners::class, 'id', 'cleaner_id');
    }

    public function user() {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

}
