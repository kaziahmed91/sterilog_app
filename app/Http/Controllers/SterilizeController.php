<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

use App\Services\SterilizerPrintService;
use App\Services\SterilizerService;

use Codedge\Fpdf\Fpdf\Fpdf as FPDF;
use Carbon\Carbon;
use Auth;
use Exception;
use Session;
use App\Companies as CompaniesModel;
use App\User as UserModel;
use App\SoftUser as SoftUserModel;
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
        $operators = SoftUserModel::where('company_id', \Auth::user()->company_id)  ->get();
        return $operators ? $operators : null;
    }
    public function getSterilizer ($id) {

        $sterilzier = SterilizeModel::where('company_id', \Auth::user()->company_id)->where('id', $id)->first();

        return $sterilzier ? $sterilzier : null;
    }


    public function getPrivateKey(Request $request, SterilizerPrintService $printService)
    {
        try {
            $privateKey = $printService->getPrivateKey();
            $printer = \Auth::user()->current_printer;
            if ( is_null($printer) ){
                $printer = '';
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }
        return  response()->json(['response' => 'success', 'privateKey' => $privateKey, 'printer' => $printer]);
    }

    public function index () {
        $cleaners = $this->getCleaners();
        $sterilizers = $this->getSterilizers();
        $operators = $this->getOperators();

        $checked = Auth::user()->type_5;
        $cycle = CyclesModel::where('company_id', \Auth::user()->company_id)->whereNull('deleted_at')->orderBy('id', 'Desc')->first();

        $log_number = $cycle ? $cycle->cycle_number + 1 : 0;

        return view('auth.sterilize', [
            'sterilizers' => $sterilizers, 
            'cleaners' => $cleaners, 
            'operators' => $operators, 
            'log_number'=> $log_number, 
            'checked' => $checked
            ]);
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
            ->with('entryUser')
            ->with('removalUser')
            ->with('cleaners')
            ->with('sterilizer')
            ->orderBy('created_at', 'desc')
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


    public function sterilize(Request $request, SterilizerService $sterilizerService, SterilizerPrintService $printService)
    {   
        $data = $request->all();
        $logged_in = $request->session()->get('softUser_userName');
        // error_log(print_r($data,true));
        if (Gate::allows('write_access') )
        {
            $logged_in = $request->session()->get('softUser_userName');
            $user = SoftUserModel::where('company_id', \Auth::user()->company_id)
                ->where('user_name', $logged_in)->first();
            try {
                $file_and_path_array = $sterilizerService->sterilize($user,$request, $printService);
            } catch (Exception $e) {
                error_log($e->getMessage());
                error_log($e->getLine());
                return response()->json(['response' => 'error', 'message' => 'Error creating sterilization stickers!', 'line' => $e->getLine() ],500);
            }

            return  response()->json(['response' => 'success',  'printFiles' => $file_and_path_array['printFiles'], 'filepaths' => $file_and_path_array['filepaths']], 200);

        } else {
            return response()->json(['response' => 'error',  'message' => 'Please log in to continue']);
        }
    }

    public function updateCycle ( Request $request, SterilizerService $sterilizerService)
    {
        $data = $request->all();
        if (Gate::allows('write_access') )
        {
            $logged_in = $request->session()->get('softUser_userName');
            $user = SoftUserModel::where('company_id', \Auth::user()->company_id)
                ->where('user_name', $logged_in)->first();
            try {
                $updatedCycle = $sterilizerService->updateCycle($user,$data);
            } catch (Exception $e) {
                error_log($e->getMessage());
                error_log($e->getLine());
                return response()->json(['response' => 'error', 'message' => 'Error updating sterilization cycle!', 'line' => $e->getLine() ],500);
            }

            return response()->json(['response' => 'success', 'message' => 'Cycle has been updated succesfully!'], 200);

        } else {
            return response()->json(['response' => 'error',  'message' => 'Please log in to continue']);
        }
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




    public function deletePdf (Request $request)
    {
        $data = $request->all();
        try {
            unlink($data['filepaths']);
        } catch (Exception $e) {
            error_log($e->getMessage());
            error_log($e->getLine());
            return response()->json($e->getMessage(), $e->getCode());
        }
        return response()->json(['response' => 'success'], 200);

    }

}
