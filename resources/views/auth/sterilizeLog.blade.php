@extends('layouts.app')

@section('content')
    @include('includes.errorbar')
    @include('includes.topbar')

    <div class=" border-top border-bottom p-2 mb-3">
        <h1 class="text-lg-center">Sterilizer Log</h1>
    </div>

    <div class="container h-70 w-40">        
        
        {{-- <div class=" mx-4 mt-3  row header  border-bottom">
            <p class='display-4 col'>Sterilizer Log</p>
            <div class="row align-items-center mx-4 ">
                <span class="border mx-2" style="height:80px; position:relative;"></span>

                <a href="{{ route('sterile') }}" class="ml-5 btn btn-primary topRight-icon btn-lg">
                    <img class="tinyIcon" src="{{asset('icons/sterilizer_icon.png')}}" alt="">
                    Sterilize
                </a>
            </div>
        </div> --}}

        <div class="">


        <form action="/sterilize/filter" role="form" method="get" class='row'>
            {{ csrf_field() }}

            <div class="form-group col-md-2">
                <label for="daterange" >Date Range</label>
                <input type="text" class=' form-control' type="text" name="daterange" data-disable-touch-keyboard>
            </div>

            <div class="form-group col-md-2">
                <label for="operator">Operators</label>
                <select class="form-control" name="operator" >
                    <option value=''>Select One</option>
                    @foreach($operators as $operator)
                        <option 
                             value="{{$operator->first_name.' '.$operator->last_name }}"> 
                            {{$operator->first_name.' '.$operator->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="sterilizer">Sterilizer</label>
                <select class="form-control" name="sterilizer" placeholder="Select One">
                    <option value=''>Select One</option>
                    @foreach($sterilizers as $sterilizer)
                        <option value="{{ $sterilizer->sterilizer_name }}"> {{$sterilizer->sterilizer_name}}</option>
                    @endforeach                
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="package">Package</label>
                <select class="form-control" name="package">
                    <option value=''>Select One</option>
                    @foreach($cleaners as $cleaner)
                        <option value="{{ $cleaner->name }}"> {{$cleaner->name}}</option>
                    @endforeach 
                </select>
            </div>
            <div class="form-group col-md-2">
              <label for="cycle">Cycle</label>
              <input type="number" class="form-control" name="cycle" aria-describedby="helpId" placeholder="">
            </div>
      
            <div class="form-group d-flex align-items-end col-sm-2">
                <button type="submit" type="submit" class="btn btn-primary mr-2">Search</button>
                <a href="{{url('/sterilize/log')}}" class="btn btn-secondary ">Reset</a> 
            </div>

        </form>

        <div class="card">

            <div class="card-body table-responsive">
                <table class="table table-sm table-striped table-hover ">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" width="90" >Date</th>
                            <th scope="col" width="90">Time</th>
                            <th scope="col" width="100">Entry Operator</th>
                            <th scope="col" width="100">Sterilizer</th>
                            <th scope="col" width='30'>Cycle #</th>
                            <th scope="col" width="20">Package</th>
                            <th scope="col" width="20"># of Packages</th>
                            <th scope="col" width='50'>Type 1</th>
                            <th scope="col" width='50'>Type 4</th>
                            <th scope="col" width='50'>Type 5</th> 
                            <th scope="col" width='50'>Parameters Verified</th>
                            <th scope="col" width="150">Comment</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        @foreach($activeCycles as $cycle)
                        <tr class="pointer" 
                        data-cycleId="{{$cycle['id']}}"
                        data-type5 = "{{$cycle['type_5_testing']}}"
                        data-cycleCompleted = "{{!is_null($cycle['completed_by'])}}"
                        data-completedOn= 
                        "{{ 
                            Carbon\Carbon::parse($cycle['completed_on'])->format('d-m-Y ')
                        }}"
                        data-completedBy= 
                        "{{$cycle['removalUser']['first_name'].' '.
                                $cycle['removalUser']['last_name'] }}"
                        data-target="#activeSterilizeModal" 
                        data-toggle="modal" 
                        data-entryComment ="{{$cycle['comment']}}"
                        >
                        <td class="ent_date">
                            {{ 
                                    Carbon\Carbon::parse($cycle['created_at'])->format('d-m-Y ')
                                }}
                            </td>
                            <td class='ent_time'>
                                {{ 
                                    Carbon\Carbon::parse($cycle['created_at'])->format('h:i:s A')
                                }}
                            </td>  
                            <td class="ent_user">
                                {{
                                    $cycle['entryUser']['first_name'].' '.
                                    $cycle['entryUser']['last_name'] 
                                }}
                            </td>
                            <td class='sterilizer'>
                                {{$cycle['sterilizer']['sterilizer_name']}}
                            </td>
                            <td class='cycle_number'>{{$cycle['cycle_number']}}</td>
                            <td class="package">{{$cycle['cleaners']['name']}}</td>
                            <td class="units_printed">{{$cycle['units_printed']}}</td>
                            <td class="type1">
                                @if($cycle['type_1'] === 1)
                                Sterile
                                @endif
                                @if($cycle['type_1'] === 0)
                                Unsterile
                                @endif
                            </td>
                            <td class="type4">
                                @if($cycle['type_4'] === 1)
                                Sterile
                                @endif
                                @if($cycle['type_4'] === 0)
                                Unsterile
                                @endif
                            </td>
                            <td class="type5">
                                @if($cycle['type_5'] === 1)
                                Sterile
                                @endif
                                @if($cycle['type_5'] === 0)
                                Unsterile
                                @endif
                            </td>
                            <td class="params_verified">                     
                                @if($cycle['params_verified'] === 1)
                                Verified
                                @endif
                                @if($cycle['params_verified'] === 0)
                                Unverified
                                @endif
                            </td>
                            <td class="comment text  w-150"><span>{{$cycle['comment']}}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </div>
        <div class="mx-auto">
            {{ $activeCycles->appends(request()->except('page'))->links('vendor/pagination/bootstrap-4') }}
        </div>
    </div>

    <div class="modal fade" id="activeSterilizeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modal_header">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                
            <div class="modal-body mx-3 input">

                <p>Package <span id="package"></span> sterilized by <span id="operator"></span> on <span id="date"></span>. </p>

                <div class="switchRow d-flex">
                    <p class="font-weight-bold">Type 1 Test Status</p>
                    <label class="switch switch-flat">
                        <input class="switch-input" id="type_1_switch" type="checkbox" />
                        <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                </div>
                
                <div class="switchRow d-flex">
                    <p class="font-weight-bold">Type 4 Test Status</p>
                    <label class="switch switch-flat">
                        <input class="switch-input" id="type_4_switch" type="checkbox" />
                        <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                </div>

                <div class="switchRow d-flex">
                    <p class="font-weight-bold">Type 5 Test Status</p>
                    <label class="switch switch-flat">
                        <input class="switch-input" id="type_5_switch" type="checkbox" />
                        <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                </div>

                <div class="switchRow d-flex  align-items-center">
                    <p class="font-weight-bold">Apply status change to entire cycle</p>
                    <label class="switch switch-slide">
                        <input class="switch-input" id="batch" type="checkbox" />
                        <span class="switch-label" data-on="Yes" data-off="No"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="additional_comments font-weight-bold">Additional Comments</label>
                    <textarea class='form-control' id="additional_comments" rows="2" ></textarea>
                </div>
            </div>

            <div class="modal-footer input">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class=" btn btn-primary logChanges" >Log changes</button>
            </div>

        {{-- Following is displayed for confirmation --}}

            <div class="modal-body mx-3  confirm">
                <p>Does the sterilizer mechanical dislay, printout or USB indicate that the parameters of time, temperature, and pressure have been met? </p>
                <a class="confirmLogChanges btn btn-success btn-lg" data-verified='1' href="#" role="button">Yes - parameters met</a>
                <a class="confirmLogChanges btn btn-danger btn-lg" data-verified='0' href="#" role="button">No - parameters failed</a>
                <br><br>
                <button type="button" data-verified='null' class="addChanges btn btn-info btn-lg btn-block">No - not checked</button>
            </div>

        {{-- The following is displayed if user clickes an updated row --}}
            <div class="modal-body mx-3  completed">
                <div class="d-flex">
                    <div class="col-sm-6 row ">
                        <p class='font-weight-bold'>Entry Date:&nbsp;</p>
                        <p id="entry_date"></p>
                    </div>
                    <div class="col-sm-auto row">
                        <p class='font-weight-bold'>Verification Date:&nbsp;</p>
                        <p id="removal_date"></p>
                    </div>
                </div>
                
                <div class="d-flex">
                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Entry Operator:&nbsp; </p>
                        <p id="ent_operator"></p>
                    </div>

                    <div class="col-sm-auto row">
                        <p class='font-weight-bold'>Verification Operator:&nbsp; </p>
                        <p id="remov_operator"></p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="col-sm-6 row">
                        <p class='font-weight-bold'>Sterilizer:&nbsp; </p>
                        <p id="sterilizer"></p>
                    </div>
                    <div class="col-sm-auto row">
                        <p class='font-weight-bold'>Cycle Number:&nbsp; </p>
                        <p id="cycle_num"></p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="col-sm-4 row">
                        <p class='font-weight-bold'>Type 1 Status:&nbsp; </p>
                        <p id="type_1_status"></p>
                    </div>

                    <div class="col-sm-4 row">
                        <p class='font-weight-bold'>Type 4 Status:&nbsp;</p>
                        <p id="type_4_status"></p>
                    </div>
                    <div class="col-sm-4 row">
                        <p class='font-weight-bold'>Type 5 Status:&nbsp;</p>
                        <p id="type_5_status"></p>
                    </div>
                </div>

                <div class="d-flex">
                    <p class='font-weight-bold'>Entry Comment: </p>&nbsp;&nbsp;
                    <p id="ent_comment"></p>
                </div>
                <div class="form-group">
                    <label for="comment"class="font-weight-bold">Additional Comments</label>
                    <textarea class='form-control' id="addtl_comment" rows="2" ></textarea>
                </div>

            </div>
              

            <div class="modal-footer completed">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class=" btn btn-primary" >Update</button>
            </div>

        </div>
    </div>
</div>

    @section('script')
        <script src="{{asset('js/sterilize.js')}}"></script>
    @endsection
@endsection