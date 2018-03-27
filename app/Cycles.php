<?php

namespace App;
use App/Sterilizer;

use Illuminate\Database\Eloquent\Model;

class Cycles extends Model
{
    public function sterilizer () {
        return $this->hasMany(Sterilizer::class, 'sterilzier_id', 'sterilizer_id');
    }
}
