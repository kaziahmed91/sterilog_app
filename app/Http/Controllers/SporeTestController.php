<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\CsvDownloadService; 

use App\SporeTest as SporeTestModel;
use App\Cycles as CyclesModel;
use App\User as UserModel;
use App\Sterilizer as SterilizerModel;
use App\SoftUser as SoftUserModel;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\SterilizeController;
use Carbon\Carbon;
use Session; 

class SporeTestController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function getActiveTests()
    {
        $activeTests = SporeTestModel::where('company_id', \Auth::user()->company_id)
                        ->whereNull('removal_at')->with('entryUser')->with('sterilizer')->get()->toArray();
        return $activeTests;
    }
    
    public function getOperators()
    {
        $operators = SoftUserModel::where('company_id', \Auth::user()->company_id)->whereNull('deleted_at')->get();
        return $operators ? $operators : null;
    }
    public function getSterilizers() 
    {
        $sterilizers = SterilizerModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();
        return $sterilizers ? $sterilizers : null;
    }

    public function getSterilizer ($id) {

        $sterilzier = SterilizerModel::where('company_id', \Auth::user()->company_id)->where('id', $id)->first();

        return $sterilzier ? $sterilzier : null;
    }

    public function getCleaner ($id)
    {
        $cleaner = CleanersModel::where('company_id', \Auth::user()->company_id)->where('id', $id)->first();
        return $cleaner ? $cleaner : null;
    }

    public function log() {
        $sterilizers = $this->getSterilizers();
        $operators = $this->getOperators();
        $allTests = SporeTestModel::where('company_id', \Auth::user()->company_id)->whereNotNull('removal_at')
                        ->with('entryUser','removalUser','sterilizer')->orderBy('removal_at','asc')->paginate(15); ;
        return view('auth.sporeLog', ['tests'=> $allTests, 'sterilizers'=> $sterilizers, 'operators'=>$operators]);
    }

    public function index() {

        $sterilizers = SterilizerModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();
        $cycle = CyclesModel::where('company_id', \Auth::user()->company_id)->whereNull('deleted_at')->first();
        $cycle_number = $cycle ? $cycle->cycle_number : 0;
        $lot = SporeTestModel::where('company_id', \Auth::user()->company_id)->orderBy('id', 'desc')->first()['lot_number'];
        // error_log($lot['lot_number']);
        $activeTests = $this->getActiveTests();

        return view('auth.spore', ['activeTests' => $activeTests, 'sterilizers' => $sterilizers, 'cycle_number' => $cycle_number, 'lot_number' => $lot]);
    }

    public function filter (Request $request, CsvDownloadService $downloadService) 
    {
        $sterilizers = $this->getSterilizers();
        $operators = $this->getOperators();

        $query = SporeTestModel::where('company_id',\Auth::user()->company_id )->whereNotNull('removal_at') ;
        $queries = [];
        // dd($request->all());    
        if ($request->has('daterange') && !is_null($request->input('daterange') ))
        {
            $dates = explode(' ',$request->input('daterange') );
            $from = Carbon::parse($dates[0]);
            $to = Carbon::parse($dates[2]);

            $query->whereBetween('entry_at', [$from, $to] );
            $queries['daterange'] = $request->input('daterange');
        } 

        if ($request->has('entry_operator') && !is_null($request->input('entry_operator')))
        {
            $query->whereHas('entryUser', function ($user) use ($request) {
                $user->where('id' ,$request->input('entry_operator'));
            });
            $queries['entry_operator'] = $request->input('entry_operator');
        }
        if ($request->has('removal_operator') && !is_null($request->input('removal_operator')))
        {
            $query->whereHas('removalUser', function ($user) use ($request) {
                $user->where('id' ,$request->input('removal_operator'));
            });
            $queries['removal_operator'] = $request->input('removal_operator');
        }

        if ($request->has('sterilizer') && !is_null($request->input('sterilizer') ))
        {
            $query->whereHas('sterilizer', function ($sterilizer) use ($request) {
                $sterilizer->where('id', $request->input('sterilizer'));
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
        if ($request->has('lot') && !is_null($request->input('lot')))
        {
             $query->where('lot_number', $request->input('lot'));
            $queries['lot'] = $request->input('lot');
        }
        
        if ($request->has('action') && $request->get('action') === 'Filter' ){

            $cycle = $query->paginate(15)->appends($queries);
            return view('auth.sporeLog', ['tests'=> $cycle, 'sterilizers'=> $sterilizers, 'operators'=>$operators]);

        } else if ($request->has('action') && $request->get('action') === 'Download' ) {
            // dd($query);
            $send = $downloadService->downloadCsv($query, 'spore');
            $send->send();
        }
    }

    public function getSoftUser(Request $request) {
        $logged_in = $request->session()->get('softUser_userName');
        $user = SoftUserModel::where('company_id', \Auth::user()->company_id)
        ->where('user_name', $logged_in)->first();
        return $user;
    }

    public function createSporeTest(Request $request)
    {
        $data = $request->all();
        if (Gate::allows('write_access') )
        {      
            if (!$request->has('sterilizer_id') || !$request->has('lot_number')){
                return response()->json(['response' => 'error',  'message' => 'A sterilizer and lot number is required to create a spore test!'],400);
            }
            $sterilizer = $this->getSterilizer($data['sterilizer_id']);
            $cycle_number = $sterilizer->cycle_number;
            $creator = $this->getSoftUser($request);

            $test = SporeTestModel::create([
                'entry_operator_id' => $creator->id,
                'company_id' => \Auth::user()->company_id, 
                'test_sterile' => $request->input('test_sterile',0) , 
                'control_sterile' => $request->input('control_sterile',0), 
                'initial_comments' => $data['comment'], 
                'sterilizer_id' => $data['sterilizer_id'],
                'entry_cycle_number' =>$cycle_number, 
                'lot_number' => $request->input('lot_number',0), 
                'entry_at' => Carbon::now() 
            ]);

            $entryLog = [
                'id' => $test->id,
                'date' => Carbon::now()->format('d-m-Y'), 
                'time' => Carbon::now()->format('h:i:s A'), 
                'creator' => $creator->first_name.' '.$creator->last_name, 
                'sterilizer' => $sterilizer->sterilizer_name, 
                'cycle' => $cycle_number, 
                'lot' => $data['lot_number'], 
                'control' => 0, 
                'test' => 0, 
                'comment' => $data['comment'] == null ? '' : $data['comment']
            ];

            return response()->json(['response' => 'success', 'log' => $entryLog], 200);

        } else {
            return response()->json(['response' => 'error',  'message' => 'Please log in to continue'], 403);
        }
    } 

    public function updateSporeTest (Request $request)
    {
        $data = $request->all(); 
        $test = SporeTestModel::where('id', $data['cycle_id'])->first();

        if (Gate::allows('write_access') )
        {
            $softUser = $this->getSoftUser($request);
            if ($test) {
                $test->control_sterile = $data['control_sterile']; 
                $test->test_sterile = $data['test_sterile'];
                $test->additional_comments = $data['additional_comments'];
                $test->removal_operator_id = $softUser->id;
                $test->removal_at = Carbon::now();
                $test->save();
            }

            $entryLog = [
                'id' => $test->id,
                'date' => Carbon::now()->format('d-m-Y'), 
                'time' => Carbon::now()->format('h:i:s A'), 
                'remover' => $softUser->first_name.' '.$softUser->last_name, 
                'control' => $data['control_sterile'] === '1' ? "Sterile" : 'Unsterile', 
                'test' => $data['test_sterile'] === '1' ? "Sterile" : 'Unsterile',
                'comment' => $data['additional_comments'] == null ? '' : $data['additional_comments']
            ];


            return response()->json(['response' => 'success', 'log' => $entryLog], 200);

        } else {
            return response()->json(['response' => 'error',  'message' => 'Please log in to continue'], 403);
        }

    }

    public function updateSporeComment (Request $request)
    {
        $data = $request->all();
        $test = SporeTestModel::where('id', $data['cycle_id'])->first();
        $test->additional_comments = $data['additional_comment'];
        if (!$test->save()) {
            return response()->json(['response' => 'error saving comment'], 500);
        }

        return response()->json(['response' => 'success'], 200);
    }

}
