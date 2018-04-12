<?php

namespace App\Services;

use App\Services\FpdfLabelService as FpdfLabelService;
use Carbon\Carbon; 
use Exception;

class SterilizerService 
{

    public function __construct( )
    {
    }

    public function filter ($request)
    {
        $query = CyclesModel::where('company_id',\Auth::user()->company_id );
        $queriers = [];
        if ($request->has('daterange') && !is_null($request->input('daterange') ))
        {
            $dates = explode(' ',$request->input('daterange') );
            $from = Carbon::parse($dates[0]);
            $to = Carbon::parse($dates[2]);

            $query->whereBetween('created_at', [$from, $to] );
            $queries['daterange'] = $request->input('daterange');
        } 

        if ($request->has('operator') && !is_null($request->input('operator')))
        {
            $query->whereHas('user', function ($user) use ($request) {
                $user->where('name', 'like'  ,$request->input('operator'));
            });
            $queries['operator'] = $request->input('operator');
        }

        if ($request->has('sterilizer') && !is_null($request->input('sterilizer') ))
        {
            $query->whereHas('sterilizer', function ($sterilzier) use ($request) {
                $sterilzier->where('sterilizer_name','like' , $request->input('sterilizer'));
            });
            $queries['sterilizer'] = $request->input('sterilizer');
        }

        if ($request->has('package') && !is_null($request->input('package')))
        {
             $query->whereHas('cleaners', function ($cleaner) use ($request) {
                $cleaner->where('name', 'like' ,$request->input('package'));
            });
            $queries['package'] = $request->input('package');
        }

        $cycle = $query->paginate(15)->appends($queries);

        return $cycle;
    }
}