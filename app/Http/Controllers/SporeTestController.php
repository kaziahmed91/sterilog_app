<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SporeTest as SporeTestModel;
use App\Cycles as CyclesModel;
use App\Sterilizer as SterilizerModel;
use App\Http\Controllers\SterilizeController;
use Carbon\Carbon; 

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

    public function log() {
        $allTests = SporeTestModel::where('company_id', \Auth::user()->company_id)->whereNotNull('removal_operator_id')
                        ->with('entryUser')->with('removalUser')->with('sterilizer')->get()->toArray();
        // return $allTests;
        return view('auth.sporeLog', ['tests'=> $allTests]);
    }

    public function index() {

        $sterilizers = SterilizerModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();   
        $cycle = CyclesModel::where('company_id', \Auth::user()->company_id)->whereNull('deleted_at')->orderBy('id', 'Desc')->first();
        $cycle_number = $cycle ? $cycle->cycle_number : 0;        
        $activeTests = $this->getActiveTests();

        return view('auth.spore', ['activeTests' => $activeTests, 'sterilizers' => $sterilizers, 'cycle_number' => $cycle_number]);
    }

    // public function createSporeTest() 
    // {
    //     $sterilizers = SterilizerModel::where('company_id', \Auth::user()->company_id)->whereNull('date_deleted')->get();   
    //     return view('auth.createSporeTest', ['sterilizers'=> $sterilizers]);
    // }

    public function createSporeTest(Request $request)
    {
        $data = $request->all();
                
        $test = SporeTestModel::create([
            'entry_operator_id' => \Auth::user()->id, 
            'company_id' => \Auth::user()->company_id, 
            'test_sterile' => $request->input('test_sterile',0) , 
            'control_sterile' => $request->input('control_sterile',0), 
            'initial_comments' => $data['comment'], 
            'sterilizer_id' => $data['sterilizer_id'],
            'entry_cycle_number' =>$request->input('cycle_number',0), 
            'lot_number' => $request->input('lot_number',0), 
            'entry_at' => Carbon::now() 
        ]);

        return response()->json(['response' => 'success', 'test' => $test], 200);
    } 

    public function updateSporeTest (Request $request)
    {
        $data = $request->all();
        
        $test = SporeTestModel::where('id', $data['cycle_id'])->first();

        if ($test)
            $test->control_sterile = $data['control_sterile']; 
            $test->test_sterile = $data['test_sterile'];
            $test->additional_comments = $data['additional_comments'];
            $test->removal_operator_id = \Auth::user()->id;
            $test->removal_at = Carbon::now();
            $test->save();

        return response()->json(['response' => 'success', 'test' => $test], 200);

    }


}
