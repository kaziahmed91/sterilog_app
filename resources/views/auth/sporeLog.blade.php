@extends('layouts.app')
@section('content')
    @include('includes.navbar') 
<div class="container">
    <div class="card m-4 ">
        <div class=" m-4 mb-0 row header  border-bottom">
            <p class='display-4'>Spore Test</p>
        </div>
        <div class="card-body">

            <div class="row m-auto d-flex  ">
                <button data-target="#addSporeTestModal" data-toggle="modal"  class="btn btn-primary btn-lg mr-2" >Add Test</button>
                <a href="{{route('spore.logs')}}" class="btn btn-info btn-lg">View Logs</a>
            </div>

            <br><br>

            <div class="card">
                <div class="card-header">Active Tests</div>
                <div class="card-body">
                    <table class="table table-sm table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Entry Date</th>
                                <th>Removal Date</th>
                                <th>Entry By</th>
                                <th>Removed By</th>
                                <th>Sterilizer</th>
                                <th>Cycle#</th>
                                <th>Lot#</th>
                                <th>Control Vial</th>
                                <th>Test Vial</th>
                                <th>Initial Comment</th>
                                <th>Additional Comment</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($tests as $test)
                                <tr class="pointer" data-target="#activeTestModal" data-testId="{{$test['id']}}" data-toggle="modal" >
                                    <td class="entryDt">
                                        {{ 
                                        Carbon\Carbon::parse($test['entry_at'])->format('d-m-Y ')
                                        }}</td>
                                    <td class="removDt">
                                        {{  
                                        Carbon\Carbon::parse($test['removal_at'])->format('d-m-Y')
                                        }}</td>  

                                    {{-- <td class="entryTm="{{  Carbon\Carbon::parse($test['entry_at'])->format('h:i:s A')}}">{{  
                                        Carbon\Carbon::parse($test['entry_at'])->format('h:i:s A')
                                        }}</td>   --}}

                                    <td class="entryUser">{{$test['entry_user'][0]['name']}}</td>
                                    <td class="removUser">{{$test['removal_user'][0]['name']}}</td>
                                    <td class="sterilizer">{{$test['sterilizer']['sterilizer_name']}}</td>
                                    <td class="cycle">{{$test['entry_cycle_number']}}</td>
                                    <td class="lot">{{$test['lot_number']}}</td>
                                    <td class="control">{{$test['control_sterile'] == 0 ? 'Unsterile' : 'Sterile'}}</td>
                                    <td class="test">{{$test['test_sterile'] == 0 ? 'Unsterile' : 'Sterile'}}</td>
                                    <td class="entComm">{{$test['initial_comments']}}</td>
                                    <td class="removComm"> {{$test['additional_comments']}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="activeTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerText">Complete Spore Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body mx-4">

                <div class="row">
                    <div class="col-sm-6 row ">
                        <p class='font-weight-bold'>Entry Date:&nbsp;</p>
                        <p id="entry_date"></p>
                    </div>
                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Removal Date:&nbsp;</p>
                        <p id="removal_date"></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Entry Operator:&nbsp; </p>
                        <p id="ent_operator"></p>
                    </div>

                    <div class="col-sm-auto row">
                        <p class='font-weight-bold'>Removal Operator:&nbsp; </p>
                        <p id="remov_operator"></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Cycle Number:&nbsp; </p>
                        <p id="cycle_num"></p>
                    </div>

                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Lot Number:&nbsp;</p>
                        <p id="lot_num"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Control Vial:&nbsp; </p>
                        <p id="control"></p>
                    </div>

                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Test Vial:&nbsp;</p>
                        <p id="test"></p>
                    </div>
                </div>

                <div class="row">
                    <p class='font-weight-bold'>Entry Comment: </p>
                    <p id="ent_comment"></p>
                </div>

                <div class="">
                    <label for="initial_comments">Additional Comments</label>
                    <textarea class='form-control' id="additional_comments" rows="2" ></textarea>
                </div>
                        
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateSporeTest">Update</button>
            </div>

        </div>
    </div>
</div>


    @section('script')  
        <script src="{{asset('js/spore-log.js')}}"></script>
    @endsection
@endsection()

