@extends('layouts.app')

@section('content')
    @include('includes.navbar')

    <div class="container card border h-70 w-40">
        
        <div class="row header  border-bottom mx-2">
            <p class='display-4'> Sterilizer Log </p>
        </div>

        <br>
        

        <form action="/sterilize/filter" role="form" method="get" class='row'>
            {{ csrf_field() }}

            <div class="form-group col-md-2">
                <label for="daterange">Date Range</label>
                <input type="text" class=' form-control' type="text" name="daterange">
            </div>

            <div class="form-group col-md-2">
                <label for="operator">Operators</label>
                <select class="form-control" name="operator" id="">
                    <option value=''>Select One</option>
                    @foreach($operators as $operator)
                        <option 
                            value="{{ $operator->name }}"> 
                            {{$operator->name}}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="sterilizer">Sterilizer</label>
                <select class="form-control" name="sterilizer" id="">
                    <option value=''>Select One</option>
                    @foreach($sterilizers as $sterilizer)
                        <option value="{{ $sterilizer->sterilizer_name }}"> {{$sterilizer->sterilizer_name}}</option>
                    @endforeach                
                </select>
            </div>

            <div class="form-group col-md-2">
                <label for="package">Package</label>
                <select class="form-control" name="package" id="">
                    <option value=''>Select One</option>
                    @foreach($cleaners as $cleaner)
                        <option value="{{ $cleaner->name }}"> {{$cleaner->name}}</option>
                    @endforeach 
                </select>
            </div>
            <div class="form-group col-md-2">
              <label for="cycle">Cycle</label>
              <input type="number" class="form-control" name="cycle" id="" aria-describedby="helpId" placeholder="">
            </div>
      
            <div class="form-group d-flex align-items-end col-sm-2">
                <button type="submit" type="submit" id="" class="btn btn-primary mr-2">Search</button>
                <a href="{{url('/sterilize/log')}}" type="" id="" class="btn btn-secondary ">Reset</a> 
            </div>

        </form>

        
        <br>
        <table class="table table-sm table-hover ">
            <thead class="thead-light">
                <tr>
                    <th scope="col" >Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Operator</th>
                    <th scope="col">Sterilizer</th>
                    <th scope="col">Cycle #</th>
                    <th scope="col">Package</th>
                    <th scope="col"># of Packages</th>
                    {{--  <th scope="col">Type 1 Status</th>
                    <th scope="col">Type 4 Status</th>
                    <th scope="col">Type 5 Status</th>  --}}
                    <th scope="col">Comment</th>
                </tr>
            </thead>

            <tbody>
                @foreach($activeCycles as $cycle)
                    <tr class="pointer" data-cycleId="{{$cycle['id']}}" data-target="#activeSterilizeModal" data-toggle="modal" >
                        <td class="date">{{ 
                            Carbon\Carbon::parse($cycle['created_at'])->format('d-m-Y ')
                            }}</td>
                        <td>{{ 
                            Carbon\Carbon::parse($cycle['created_at'])->format('h:i:s A')
                            }}</td>   
                        <td class="user_name">{{$cycle['user'][0]['name']}}</td>
                        <td>{{$cycle['sterilizer']['sterilizer_name']}}</td>
                        <td class='cycle_number'>{{$cycle['cycle_number']}}</td>
                        <td class="package">{{$cycle['cleaners']['name']}}</td>
                        <td class="units_printed">{{$cycle['units_printed']}}</td>
                        <td>{{ $cycle['comment'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        

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
              
            <div class="modal-body mx-3">

                <p>Package <span id="package"></span> sterilized by <span id="operator"></span> on <span id="date"></span>. </p>

                <div class="row switchRow ">
                    <p>Type 1 Test Status</p>
                    <label class="switch switch-flat">
                        <input class="switch-input" id="type_1" type="checkbox" />
                        <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                </div>
                
                <div class="row switchRow">
                    <p>Type 4 Test Status</p>
                    <label class="switch switch-flat">
                        <input class="switch-input" id="type_4" type="checkbox" />
                        <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                </div>

                <div class="row switchRow">
                    <p>Type 5 Test Status</p>
                    <label class="switch switch-flat">
                        <input class="switch-input" id="type_5" type="checkbox" />
                        <span class="switch-label" data-on="Sterile" data-off="Unsterile"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                </div>

                <div class="row">
                    <p>Apply status change <br> to entire cycle</p>
                    <label class="switch switch-slide">
                        <input class="switch-input" id="batch" type="checkbox" />
                        <span class="switch-label" data-on="Yes" data-off="No"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="addChanges btn btn-primary">Log changes</button>
            </div>

        </div>
    </div>
</div>

    @section('script')
        <script src="{{asset('js/sterilize.js')}}"></script>
    @endsection
@endsection