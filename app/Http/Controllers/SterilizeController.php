<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\SterilizerPrintService;
use App\Services\SterilizerService;

use Codedge\Fpdf\Fpdf\Fpdf as FPDF;
use Carbon\Carbon;
use Auth;
use Exception;
use App\Companies as CompaniesModel;
use App\User as UserModel;
use App\Sterilizer as SterilizeModel;
use App\Cleaners as CleanersModel;
use App\Cycles as CyclesModel;

class SterilizeController extends Controller
{   
    
    public function __construct() 
    {
        $this->middleware('auth');

    }

    public function getCleaners () 
    {
        $cleaners = CleanersModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();
        return $cleaners ? $cleaners : null;
    }

    public function getSterilizers () 
    {
        $sterilizers = SterilizeModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();
        return $sterilizers ? $sterilizers : null;
    }

    public function getCycles() 
    {
        $cycles = CyclesModel::where('company_id', \Auth::user()->company_id)->whereNull('deleted_at')->get();
        return $cycles ? $cycles : null;
    }
    public function getOperators()
    {
        $operators = UserModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();
        return $operators ? $operators : null;
    }
    public function getSterilizer ($id) {

        $sterilzier = SterilizeModel::where('company_id', \Auth::user()->company_id)->where('id', $id)->first();

        return $sterilzier ? $sterilzier : null;
    }

    public function getCleaner ($id)
    {
        $cleaner = CleanersModel::where('company_id', \Auth::user()->company_id)->where('id', $id)->first();
        return $cleaner ? $cleaner : null;
    }

    public function getPrivateKey(Request $request, SterilizerPrintService $printService)
    {
        try {
            $privateKey = $printService->getPrivateKey();
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }
        return  response()->json(['response' => 'success', 'privateKey' => $privateKey]);
    }

    public function index () {
        $cleaners = $this->getCleaners();
        $sterilizers = $this->getSterilizers();
        $operators = $this->getOperators();

        $cycle = CyclesModel::where('company_id', \Auth::user()->company_id)->whereNull('deleted_at')->orderBy('id', 'Desc')->first();

        $log_number = $cycle ? $cycle->cycle_number + 1 : 0;

        return view('auth.sterilize', ['sterilizers' => $sterilizers, 'cleaners' => $cleaners, 'operators' => $operators, 'log_number', $log_number]);
    }

    public function logPost(Request $request ) {
        
        return view('auth.sterilizeLog');
    } 

    public function viewLog ()  
    {
        $cleaners = $this->getCleaners();
        $sterilizers = $this->getSterilizers();
        $operators = $this->getOperators();

        $activeCycles = CyclesModel::where('company_id', \Auth::user()->company_id)
            ->with('user')
            ->with('cleaners')
            ->with('sterilizer')
            ->whereNull('completed_by')
            ->paginate(15);
            // ->get();

        return view('auth.sterilizeLog', ['activeCycles' => $activeCycles, 'sterilizers' => $sterilizers, 'cleaners' => $cleaners, 'operators'=> $operators]);
    }  

    public function filter (Request $request , SterilizerService $sterilizerService) 
    {
        $data = $request->all();
       
        $cleaners = $this->getCleaners();
        $sterilizers = $this->getSterilizers();
        $operators = $this->getOperators();

        try {
            $cycle = $sterilizerService->filter($request);
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }

        return view('auth.sterilizeLog', ['activeCycles' => $cycle, 'sterilizers' => $sterilizers, 'cleaners' => $cleaners, 'operators'=> $operators]);
    }


    public function sterilize(Request $request, SterilizerPrintService $printService)
    {   
        $data = $request->all();    
        array_filter($data['data']); //removes all empty values
        $printFiles = [];
        $filepaths = [];
        $label_data = [];
        
        foreach ( $data['data'] as $id => $value) {

            if (isset($value) && $value !== '') {

                try {
                    $cycle = CyclesModel::create([
                        'company_id' => \Auth::user()->company_id,
                        'user_id' => \Auth::user()->id,
                        'sterilizer_id'  => $data['sterilizer_id'],
                        'cleaner_id' => $id,
                        'units_printed' => $value,
                        'cycle_number' => $data['cycle_number'],
                        'comment' => $data['comment']
                    ]);
                    
                    $sterilizer = SterilizeModel::where('id', $data['sterilizer_id'])->first();
                    $sterilizer->cycle_number = $data['sterilizer_id']; 
                    
                    $label_data[] = [
                        'cycle_num' => $data['cycle_number'],
                        'units_printed' => $value, 
                        'sterilizer' => $data['sterilizer'], 
                        'cleaner' => $this->getCleaner($id)['name']
                    ];


                } catch (Exception $e) {
                    error_log($e->getMessage());
                    error_log($e->getLine());
                    return response()->json($e->getMessage(), $e->getCode());
                }


                if (!$cycle) {
                    return response()->json(['response'=> 'error! Problem creating cycle'], 500);
                }
            } 
        }
        try {
            $file = $this->generateTags($label_data, $printService);
            $printFiles[] = $file[0];
            $filepaths[] = $file[1];
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }

        return response()->json(['response' => 'success', 'printFiles' => $printFiles, 'filepaths' => $filepaths], 200);
    }

    public function logChanges ( Request $request)
    {
        $data = $request->all();

        if ($data['batch'] === '1') {
            $cycles = CyclesModel::where('company_id', \Auth::user()->company_id)->
                where('cycle_number', $data['cycle_number'])->whereNull('completed_by')->get();

            if ($cycles) {
                foreach ($cycles as $cycle) {
                    $cycle['type_1'] = $data['type1'];
                    $cycle['type_4'] = $data['type4'];
                    $cycle['type_5'] = $data['type5'];
                    $cycle['completed_by'] = \Auth::user()->id;

                    if (!$cycle->save()) {
                        return response()->json(['response'=> 'error! Problem batch saving'], 500);
                    }
                }
            } 

        } elseif ( $data['batch'] === '0')
        {
            $cycle = CyclesModel::where('company_id', \Auth::user()->company_id)->where('id', $data['cycle_id'])->first();
            if ($cycle) {
                $cycle->type_1 = $data['type1'];
                $cycle->type_4 = $data['type4'];
                $cycle->type_5 = $data['type5'];
                $cycle->completed_by = \Auth::user()->id;
            } 
            if (!$cycle->save() ){
                return response()->json(['response'=> 'error! Problem batch cycle!'], 500);
            }

        }

        return response()->json(['response' => 'success', 'data' => $data], 200);

    }

    public function signSignature (Request $request)
    {
        $data = $request->all();
        $req = $data['toSign'];

        $key_location = $_SERVER['DOCUMENT_ROOT'] . '/print_keys/key.pem';
        $pass = 'password';
        $privateKey = openssl_get_privatekey(file_get_contents($key_location, $pass));

        $signature = null;
        openssl_sign($req, $signature, $privateKey);

        if ($signature) {
            $sig_base64 =  base64_encode($signature);
            return response()->json(['response' => 'success', 'base64' => $sig_base64], 200);
        }

        return response()->json(['response' => 'error'], 500);
    }


    private function generateTags ($data, SterilizerPrintService $printService)
    {
        // error_log(print_r($data,true));

        try {
            $filepath = $printService->generatePdfTag($data); 
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }
        return $filepath;

    }

    public function deletePdf (Request $request , SterilizerPrintService $printService)
    {
        $data = $request->all();
        error_log(print_r($data,true));
        try {
            $filepath = $printService->deletePdf($data['filepaths']); 
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }
        return response()->json(['response' => 'success'], 200);

    }
}
