<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Companies;

class Cleaners extends Model
{
    protected $table = "cleaners";

    protected $fillable = ['company_id', 'added_by', 'name'];

    public function company () {
        return $this->belongsTo(Companies::class, 'company_id', 'id');
    }
}
