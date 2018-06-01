@extends('layouts.app')

@section('content')
    @include('includes.errorbar')   
    @include('includes.topbar')
    
    <div class="header-text header-container">
            <div class="headerBar">
                <p class="header">Sterilizer Load</p>
                <span id="closeNavBtn">
                    <span class="line1"></span>
                    <span class="line2"></span>
                    <span class="line3"></span>
                </span>
            </div>
            
            <ul class="menulist">
                <li><a class="menuitems" href="{{route('sterile.logs')}}">Sterilizer Logs</a></li>
                <li><a class="menuitems" href="{{route('spore')}}">Spore Tests</a></li>		
                <li><a class="menuitems" href="{{route('spore.logs')}}">Spore Logs</a></li>				
            </ul>	
        </div>
        <br>
    <div class="container  mb-3 container-margin">

            <form id="sterilizeForm" class="form-group ">
                {{ csrf_field() }}

                <div class="row mx-2">
                    <div class=" col-sm-4 ">

                        <div class="d-flex align-items-baseline">
                            <h5 class='font-weight-bold mr-2' for="sterilizer">Sterilizer</h5>
                            <select class="sterilizer sterilizerSelect custom-select"  name="sterilizer" id="sterilizer" style="height:45px !important">
                                <option selected value=''>Select one</option>
                                
                                @foreach( $sterilizers as $sterilizer )
                                    <option id="{{ $sterilizer->id }}" data-cycleNum="{{$sterilizer->cycle_number + 1}}" value="{{ $sterilizer->sterilizer_name }}">{{ $sterilizer->sterilizer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="invalid-feedback hidden" id="sterilizer_error">
                            <strong>Sterilizer Must Be Selected!</strong>
                        </div>

                    </div>

                    <div class="col-sm-4">
                        <div class="d-flex align-items-baseline">
                            <h5 for="cycle_number" class='font-weight-bold mr-2'>Cycle Number</h5>
                            <input type="number" class="form-control col-sm-6"name="" id="cycle_number" aria-describedby="helpId" placeholder="Cycle Number" style="height:45px !important">
                        </div>
                        <div class="invalid-feedback hidden" >
                            <strong>Cycle number cannot be empty!</strong>
                        </div>
                    </div>

                    <div class="d-flex align-items-center col-sm-4">
                        <h5 class='font-weight-bold'>Type 5 Test Status</h5>
                        <label class="switch switch-flat ml-3">
                            <input class="switch-input" {{ $checked ? 'checked' : '' }} id="type_5_switch" type="checkbox" />
                            <span class="switch-label" data-on="Active" data-off="Inactive"></span> 
                            <span class="switch-handle"></span> 
                        </label>
                    </div>

                </div>

                <br>
                <div class="row mx-2 mt-2 cleaners">
                    @php $count = 1; $id=1;@endphp
                    @foreach( $cleaners as $key => $cleaner )

                        @if($count %12 == 1)
                        <div class="scrollContainer" data-id="{{$id}}" style="{{ $id > 1 ? 'display:none;' : '' }}">
                        @endif

                            <div class="cleaner col-md-3 span4 input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="{{ $cleaner->id}}">{{$cleaner->name}}</span>
                                </div>
                            
                                <select name="{{ $cleaner->name }}" class="cleanerUnits sterilizeNumberDropdown form-control ">
                                    @for ($i = 0; $i <= 30; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>

                        @if($count%12 == 0)
                            @php $id++; @endphp
                            </div>
                        @endif

                        @php $count++; @endphp

                    @endforeach()

                    @if($count%12 != 1)
                        </div>
                    @endif
                    @if($id > 1)
                    <br>
                    <nav aria-label="...">
                        <ul class="pagination pagination-md d-flex justify-content-center">
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">1</a></li>
                            @for($i=2; $i<= $id; $i++)
                                <li class="page-item"><a class="page-link" href="#">{{$i}}</a></li>
                            @endfor()
                        </ul>
                    </nav>
                    @endif
                </div>

                <div class="form-group mx-4">
                    <label for="comment_form">Comments</label>
                    <textarea class='form-control' name="comment_form" id="comment" rows="2" ></textarea>
                </div>

            <div class="row d-flex align-items-center">
               
                <p class="printerStatus ml-4  col">
                    Printer Status :       
                    <span class='font-weight-bold'>Inactive</span>
                </p>

                <div class="col d-flex justify-content-around">
                    <button  class="offset-5 col-md-5 log  btn btn-primary btn-lg " type='submit'>
                        Print
                    </button>

                    </form> 
                </div>

            </div>
            
    </div>


    @section('script')
        <script src="https://cdn.rawgit.com/kjur/jsrsasign/c057d3447b194fa0a3fdcea110579454898e093d/jsrsasign-all-min.js"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/dependencies/rsvp-3.1.0.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/dependencies/sha-256.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/qz-tray.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/qz-print.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/sterilize.js')}}"></script>

    @endsection
@endsection