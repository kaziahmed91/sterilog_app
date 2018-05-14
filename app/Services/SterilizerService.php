<?php

namespace App\Services;

use App\Services\FpdfLabelService as FpdfLabelService;
use App\Services\SterilizerPrintService;
use Carbon\Carbon; 
use Exception;
use App\Companies as CompaniesModel;
use App\User as UserModel;
use App\Sterilizer as SterilizeModel;
use App\Cleaners as CleanersModel;
use App\Cycles as CyclesModel;

class SterilizerService 
{

    public function __construct()
    {
    }

    public function filter ($request)
    {
        $query = CyclesModel::where('company_id',\Auth::user()->company_id );
        $queries = [];
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
            $name = explode(' ',$request->input('operator') );
            $query->whereHas('entryUser', function ($user) use ($name) {
                $user->where('first_name', 'like', $name[0])
                ->where('last_name', 'like', $name[1]);
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
        if ($request->has('cycle') && !is_null($request->input('cycle')))
        {
             $query->where('cycle_number', $request->input('cycle'));
            $queries['cycle'] = $request->input('cycle');
        }
        

        return ['collection'=> $query, 'queries' => $queries];
    }

    private function getCleaner ($id)
    {
        $cleaner = CleanersModel::where('company_id', \Auth::user()->company_id)->where('id', $id)->first();
        return $cleaner ? $cleaner : null;
    }

    public function sterilize ($user,$data, SterilizerPrintService $printService) {
        $printFiles = [];
        $filepaths = [];
        $label_data = [];
        $batch_num = CyclesModel::where('company_id' ,\Auth::user()->company_id)->orderBy('created_at', 'DESC')->pluck('batch_number')->first();
        $batch_num += 1;

        foreach ( $data['data'] as $id => $value) {

            if (isset($value) && $value !== '' && $value > 0) {

                $label_data[] = [
                    'cycle_num' => $data['cycle_number'],
                    'units_printed' => $value, 
                    'sterilizer' => $data['sterilizer'], 
                    'cleaner' => $this->getCleaner($id)['name'], 
                    'user' => $user->first_name.' '.$user->last_name
                ];

            }
        }
        
        try {
            $file = $this->generateTags($label_data, $printService);
            $printFiles = $file[0];
            $filepaths = $file[1];
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            throw $e;

        }
        $sterilizer = SterilizeModel::where('id', $data['sterilizer_id'])->first();
        $sterilizer->cycle_number = $data['cycle_number']; 
        $sterilizer->save();

        $superUser = UserModel::where('id', \Auth::user()->id)->first();
        $superUser->type_5 = intval($data['type_5']);
        $superUser->save();

        $this->saveSterilizeCycle($data, $batch_num, $user);

        return ['printFiles' => $printFiles, 'filepaths' => $filepaths];
    }

    private function saveSterilizeCycle($data, $batch_num, $user)
    {
        foreach ( $data['data'] as $id => $value) {

            if (isset($value) && $value !== '' && $value > 0) {

                try {
                    $cycle = CyclesModel::create([
                        'company_id' => \Auth::user()->company_id,
                        'user_id' => $user->id,
                        'sterilizer_id'  => $data['sterilizer_id'],
                        'cleaner_id' => $id,
                        'units_printed' => $value,
                        'cycle_number' => $data['cycle_number'],
                        'batch_number' => $batch_num,
                        'comment' => $data['comment'],
                        'type_5_testing' => $data['type_5']
                    ]);


                } catch (Exception $e) {
                    error_log($e->getMessage());
                    error_log($e->getLine());
                    throw $e;
                }

                if (!$cycle) {
                    throw new \Exception('Cycle could not be updated', 500);
                }
            }
        }
    }

    public function updateCycle ($user, $data )
    {
        $cycle = CyclesModel::where('company_id', \Auth::user()->company_id)->where('id', $data['cycle_id'])->first();
        $type_5_testable = $cycle->type_5_testing === 1 ? true : false;

        if ($data['batch'] === '1') {
            $batch_id = $cycle->batch_number;

            $cycles = CyclesModel::where('company_id', \Auth::user()->company_id)->
                where('batch_number', $batch_id)->get();
            if ($cycles) {
                foreach ($cycles as $cycle) {
                    $cycle['type_1'] = $data['type1'];
                    $cycle['type_4'] = $data['type4'];
                    $cycle['type_5'] = $type_5_testable ? $data['type5'] : null;
                    $cycle['params_verified'] = $data['params_verified'];
                    $cycle['completed_by'] = $user->id;
                    $cycle['completed_on'] = Carbon::now();
                    $cycle['additional_comments'] = $data['comments'];

                    if (!$cycle->save()) {
                        return response()->json(['response'=> 'error! Problem batch saving'], 500);
                    }
                }
            } 

        } elseif ( $data['batch'] === '0')
        {
            if ($cycle) {
                error_log( print_r($data,true));
                $cycle->type_1 = $data['type1'];
                $cycle->type_4 = $data['type4'];
                $cycle->type_5 = $type_5_testable ? $data['type5'] : null;
                $cycle->params_verified = $data['params_verified'];
                $cycle->completed_by = $user->id;
                $cycle->additional_comments = $data['comments'];
                $cycle->completed_on = Carbon::now();
            }
            if (!$cycle->save() ){
                return response()->json(['response'=> 'error! roblem batch cycle!'], 500);
            }

        }
        $entryLog = [
            'date' => Carbon::now()->format('d-m-Y'), 
            'time' => Carbon::now()->format('h:i:s A'), 
            'remover' => $user->first_name.' '.$user->last_name, 
            'type1' => $data['type1'] === '1' ? "Sterile" : 'Unsterile', 
            'type4' => $data['type4'] === '1' ? "Sterile" : 'Unsterile', 
            'type5' => $data['type5'] === '1' ? "Sterile" : 'Unsterile',
            'params' => $data['params_verified'] === '1' ? "Yes" : 'No',
            // 'comment' => $data['comments'] == null ? '' : $data['comments']
        ];
        return $entryLog;
    } 

    private function generateTags ($data, SterilizerPrintService $printService)
    {

        try {
            $filepath = $printService->generatePdfTag($data); 
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }
        return $filepath;

    }
}