@extends('layouts.app')
@section('content')
    @include('includes.errorbar')
    @include('includes.topbar') 

    <div class=" border-top border-bottom p-2 mb-3 header-text">
            <h1 class="text-lg-center">Spore Test</h1>
        </div>
    <div class="container h-70 w-40 container-margin">        


    <div class="">

        <div class="card">
            <div class="card-header">Active Tests</div>
           
            <div class="card-body">
                <table class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Operator</th>
                            <th>Sterilizer</th>
                            <th>Cycle#</th>
                            <th>Lot#</th>
                            <th>Control Vial</th>
                            <th>Test Vial</th>
                            <th>Comment</th>
                        </tr>
                    </thead>

                    <tbody>     
                        <div class="api_tableRow ">
                            <tr class="pointer clone " data-target="#activeTestModal" data-testId="" data-toggle="modal"></tr>
                                <td class="date"></td>
                                <td class="time"></td>
                                <td class="creator"></td>
                                <td class="sterilizer"></td>
                                <td class="cycle"></td>
                                <td class="lot"></td>
                                <td class="control"></td>
                                <td class="test"></td>
                                <td class="comment"></td>
                            </td>

                        </div>
                        @foreach($activeTests as $test)
                            <tr class="pointer" data-target="#activeTestModal" data-testId="{{$test['id']}}" data-toggle="modal" >
                                <td >{{ 
                                    Carbon\Carbon::parse($test['entry_at'])->format('d-m-Y ')
                                    }}</td>
                                <td>{{ 
                                    Carbon\Carbon::parse($test['entry_at'])->format('h:i:s A')
                                    }}</td>  
                                <td>{{$test['entry_user']['first_name']}} {{$test['entry_user']['last_name']}}</td>
                                <td>{{$test['sterilizer']['sterilizer_name']}}</td>
                                <td>{{$test['entry_cycle_number']}}</td>
                                <td>{{$test['lot_number']}}</td>
                                <td></td>
                                <td></td>
                                <td>{{$test['initial_comments']}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>    
                
            </div>
        </div>
        <br>
            <button
            data-target="#addSporeTestModal" data-toggle="modal" 
            class=" d-flex justify-content-center m-auto w-50 btn btn-primary btn-lg " 
            type='submit'>Add Test</button><br>
    </div>
</div>

@include('includes.updateSporeTest-modal')

<div class="modal fade" id="addSporeTestModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a Spore Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <form action="" method="" class='addSpore'>
                    <div class="form-group">
                        <label for="sterilizer">Sterilizer</label>
                        <select class="custom-select"  name="sterilizer" id="sterilizer">

                            @foreach( $sterilizers as $sterilizer )
                                <option 
                                    data-id ="{{ $sterilizer->id }}" 
                                    value ="{{$sterilizer->sterilizer_name}}"
                                    data-cycleId ="{{ $sterilizer->cycle_number }}"
                                    >{{ $sterilizer->sterilizer_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="entry_cycle_number" class=''>Cycle Number</label>
                        <input type="number"  class="form-control" value="" name="" id="cycle_number" aria-describedby="helpId" placeholder="Cycle Number">
                    </div>
                    <div class="form-group">
                        <label for="lot_number">Lot Number</label>
                        <input type="number" class="form-control"  value='{{$lot_number}}' name="" id="lot_number" aria-describedby="helpId" placeholder="Lot Number">
                    </div>

                    <div class="form-group">
                        <label >Comments</label>
                        <textarea class='form-control' id="comments" rows="2" ></textarea>
                    </div>
                    
                </form>
            </div>
    
            <div class="modal-footer">
                <button type="button" id="closeAddSporeTestModal" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addSporeTest">Save changes</button>
            </div>

        </div>
    </div>
</div>

    @section('script')  
        <script src="{{asset('js/spore-test.js')}}"></script>
    @endsection
@endsection()

