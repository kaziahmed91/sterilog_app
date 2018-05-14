@extends('layouts.app')
@section('content')
    @include('includes.errorbar')
    @include('includes.topbar')

    <div class="header-text header-container">
        <div class="headerBar">
            <p class="header">Spore log</p>
            <span id="closeNavBtn">
                <span class="line1"></span>
                <span class="line2"></span>
                <span class="line3"></span>
            </span>
        </div>
        
        <ul class="menulist">
            <li><a class="menuitems" href="{{route('spore')}}">Spore Tests</a></li>		
            <li><a class="menuitems" href="{{route('sterile')}}">Sterilizer Load</a></li>				
            <li><a class="menuitems" href="{{route('sterile.logs')}}">Sterilizer Logs</a></li>
        </ul>	
    </div>
<div class="container container-margin" style="margin-bottom:50px;">        

        <form action="/spore/log/filter" role="form" method="get" class='row'>
            {{ csrf_field() }}

            <div class="form-group col-md-2">
                <label for="daterange">Date Range</label>
                <input type="text" class=' form-control' type="text" name="daterange" data-disable-touch-keyboard>
            </div>

            <div class="form-group col-md-2">
                <label for="entry_operator">Entry Operator</label>
                <select class="form-control" name="entry_operator" >
                    <option value=''>Select One</option>
                    @foreach($operators as $operator)
                        <option 
                            value="{{$operator->id}}"> 
                            {{$operator->first_name.' '.$operator->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="removal_operator">Removal Operator</label>
                <select class="form-control" name="removal_operator" >
                    <option value=''>Select One</option>
                    @foreach($operators as $operator)
                        <option 
                            value="{{ $operator->id }}"> 
                            {{$operator->first_name.'  '.$operator->last_name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="sterilizer">Sterilizer</label>
                <select class="form-control" name="sterilizer">
                    <option value=''>Select One</option>
                    @foreach($sterilizers as $sterilizer)
                        <option value="{{ $sterilizer->id }}"> {{$sterilizer->sterilizer_name}}</option>
                    @endforeach                
                </select>
            </div>


            <div class="form-group col-md-2">
              <label for="lot">Lot</label>
              <input type="number" class="form-control" name="lot" aria-describedby="helpId" placeholder="">
            </div> 
      
            <!-- Default dropright button -->
            <div class=" btn-group btn actions  btn-lg  col-md-2">
                <button type="button" class="btn btn-secondary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu">
                    <input class="dropdown-item pointer" type="submit" name="action" value="Filter"></input>
                    <a class="dropdown-item" href="{{url('/spore/log')}}">Reset</a>
                <div class="dropdown-divider"></div>
                    <input class="dropdown-item" type="submit"   name="action" value="Download"></input>
                </div>
            </div>

        </form>

        <div class="card">
            {{-- <div class="card-header">All Tests</div> --}}
            <div class="card-body table-responsive">
                <table class="table table-sm table-striped table-hover">
                    <thead class='thead-light'>
                        <tr>
                            <th scope="col">Entry Date</th>
                            <th scope="col">Removal Date</th>
                            <th scope="col">Entry By</th>
                            <th scope="col">Removed By</th>
                            <th scope="col" >Sterilizer</th>
                            <th scope="col">Cycle#</th>
                            <th scope="col">Lot#</th>
                            <th scope="col">Control Vial</th>
                            <th scope="col">Test Vial</th>
                            <th  scope="col">Initial Comment</th>
                            <th scope="col">Additional Comment</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($tests as $test)
                            <tr class="pointer" 
                                data-target='#completedTestModal'
                                data-testId="{{$test['id']}}" data-toggle="modal" >
                                <td class="entryDt">
                                    {{ 
                                    Carbon\Carbon::parse($test['entry_at'])->format('d-m-Y ')
                                    }}</td>
                                <td class="removDt">
                                    {{  
                                    Carbon\Carbon::parse($test['removal_at'])->format('d-m-Y')
                                    }}</td>  

                                <td class="entryUser">{{$test['entryUser']['first_name']}} {{$test['entryUser']['last_name']}}</td>
                                <td class="removUser">{{$test['removalUser']['first_name']}} {{$test['removalUser']['last_name']}}</td>
                                <td class="sterilizer">{{$test['sterilizer']['sterilizer_name']}}</td>
                                <td class="cycle">{{$test['entry_cycle_number']}}</td>
                                <td class="lot">{{$test['lot_number']}}</td>
                                <td class="control">
                                    @if( !is_null($test['removal_at']) )
                                        {{$test['control_sterile'] == 0  && is_null($test['control_sterile'])  ? 'Unsterile' : 'Sterile'}}
                                    @endif
                                </td>   
                                <td class="test">
                                    @if( !is_null($test['removal_at']) )
                                        {{$test['test_sterile'] == 0 ? 'Unsterile' : 'Sterile'}}
                                    @endif    
                                </td>
                                <td class="entComm text "><span>{{$test['initial_comments']}}</span></td>
                                <td class="removComm text"> <span>{{$test['additional_comments']}}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mx-auto">
                {{ $tests->appends(request()->except('page'))->links('vendor/pagination/bootstrap-4') }}
            </div> 
        </div>
</div>

<div class="modal fade" id="completedTestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="headerText">Completed Spore Test</h5>
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
                    <p class='font-weight-bold'>Entry Comment: </p>&nbsp;
                    <p id="ent_comment"></p>
                </div>

                <div class="form-group">
                    <label for="additional_comments font-weight-bold">Additional Comments</label>
                    <textarea class='form-control' id="additional_comments_after" rows="2" ></textarea>
                </div>
                        
            </div>
    
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary dismiss-modal"  data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateComments">Update</button>
            </div>

        </div>
    </div>
</div>

    @section('script')  
        <script src="{{asset('js/spore-test.js')}}"></script>

        <script>
            // var nav = $('.header-container');
            // var main = document.getElementById("main");
            // var menu = document.getElementsByClassName("menuitems");
            // var close = document.getElementById("closebtn");

            // //default to measure if/else from
            // nav.style.height = "50px";
            // main.style.marginTop = "50px";
            // for (i = 0; i < menu.length; i++){menu[i].style.marginTop="100px";};


            // function navToggle() {	
            //     //to close
            //     if (nav.style.height <= "275px") {
            //         nav.style.height = "50px";
            //         main.style.marginTop = "50px";
                    
            //         var i = 0;
            //         for (i = 0; i < menu.length; i++){
            //             menu[i].style.opacity="0.0";
            //             menu[i].style.marginTop="100px";
            //         };
            //         document.body.style.backgroundColor = "rgba(0,0,0,0.0)";
                
            //     } 
            //     //to open
            //     else if (nav.style.height <= "50px") {
            //         nav.style.height = "275px";
            //         main.style.marginTop = "275px";
            //         var i = 0;
            //         for (i = 0; i < menu.length; i++){
            //         menu[i].style.opacity="1.0";
            //         menu[i].style.marginTop="0px";
            //         };
            //         document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
                
            //     }

            // };

        </script>
        
    @endsection
@endsection()

