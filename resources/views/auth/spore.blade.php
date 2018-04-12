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
                            @foreach($activeTests as $test)
                                <tr class="pointer" data-target="#activeTestModal" data-testId="{{$test['id']}}" data-toggle="modal" >
                                    <td class="date">{{ 
                                        Carbon\Carbon::parse($test['entry_at'])->format('d-m-Y ')
                                        }}</td>
                                    <td>{{ 
                                        Carbon\Carbon::parse($test['entry_at'])->format('h:i:s A')
                                        }}</td>  
                                    <td>{{$test['entry_user'][0]['name']}}</td>
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
        </div>
    </div>
</div>

<div class="modal fade" id="activeTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Complete Spore Test</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <div class=''>
                    <div class="row switchRow spore-slide">
                        <p>Control Vial Sterile</p>
                        <label class="switch switch-flat">
                            <input class="switch-input" id="type_1" type="checkbox" />
                            <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                            <span class="switch-handle"></span> 
                        </label>
                    </div>
                    
                    <div class="row switchRow spore-slide">
                        <p>Test Vial Sterile</p>
                        <label class="switch switch-flat">
                            <input class="switch-input" id="type_4" type="checkbox" />
                            <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                            <span class="switch-handle"></span> 
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="initial_comments">Additional Comments</label>
                        <textarea class='form-control' name="initial_comments" id="additional_comments" rows="2" ></textarea>
                    </div>
                </div>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateSporeTest">Save changes</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addSporeTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <option selected>Select one</option>
                            @foreach( $sterilizers as $sterilizer )
                                <option id="{{ $sterilizer->id }}" value="{{ $sterilizer->sterilizer_name }}">{{ $sterilizer->sterilizer_name }}</option>
                            @endforeach

                        </select>
                        <small id="helpId" class="text-muted hidden">Help text</small>
                    </div>

                    <div class="form-group">
                        <label for="entry_cycle_number" class=''>Cycle Number</label>
                        <input type="number" class="form-control" value="{{$cycle_number}}" name="" id="cycle_number" aria-describedby="helpId" placeholder="Cycle Number">
                    </div>

                    <div class="form-group">
                        <label for="lot_number">Lot Number</label>
                        <input type="number" class="form-control" name="" id="lot_number" aria-describedby="helpId" placeholder="Lot Number">
                    </div>

                    <div class="form-group">
                        <label >Comments</label>
                        <textarea class='form-control' id="additional_comments" rows="2" ></textarea>
                    </div>
                    
                </form>
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addSporeTest">Save changes</button>
            </div>

        </div>
    </div>
</div>

    @section('script')  
        <script src="{{asset('js/spore-test.js')}}"></script>
    @endsection
@endsection()

