@extends('layouts.app')

@section('content')
    @include('includes.navbar')
    @include('includes.errorbar')
    @include('includes.topbar')
    <div class="container card border h-70 w-40 mb-3">

        <div class=" mx-4 mt-3  row header  border-bottom">

            <p class='display-4 col'>Sterilizer Load</p>

            <div class="row align-items-center mx-4 ">

                <div class="d-flex align-items-center  ">
                    <h5 class='font-weight-bold '>Type 5 Test Status</h5>
                    <label class="switch switch-flat ml-3">
                        <input class="switch-input" {{ $checked ? 'checked' : '' }} id="type_5" type="checkbox" />
                        <span class="switch-label" data-on="Active" data-off="Inactive"></span> 
                        <span class="switch-handle"></span> 
                    </label>
                </div>

                <span class="border mx-2" style="height:80px; position:relative;"></span>

                <a href="{{ route('sterile.logs') }}" class="ml-5 btn btn-primary topRight-icon btn-lg">
                    <img class="tinyIcon" src="{{asset('icons/log_icon.png')}}" alt="">
                    View Log
                </a>
            </div>
        </div>
        <div class="card-body">

            <form id="sterilizeForm" class="form-group ">
                {{ csrf_field() }}

                <div class="row mx-2 ">
                    <div class="form-group  col-sm-6 ">

                        <div class="d-flex align-items-center">
                            <label class='col-form-label mr-2' for="sterilizer">Sterilizer</label>
                            <select class="sterilizerSelect custom-select"  name="sterilizer" id="sterilizer">
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

                    <div class="form-group col-sm-6">
                        <div class="d-flex align-items-baseline">
                            <label for="cycle_number" class='col-form-label mr-2'>Cycle Number</label>
                            <input type="number" class="form-control col-sm-6"name="" id="cycle_number" aria-describedby="helpId" placeholder="Cycle Number">
                        </div>
                        <div class="invalid-feedback hidden" >
                            <strong>Cycle number cannot be empty!</strong>
                        </div>
                    </div>

                </div>

                <div class="row mx-2 mt-2 cleaners">
                    @foreach( $cleaners as $key => $cleaner )
                        <div class="col-md-4 span4 input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="{{ $cleaner->id}}">{{$cleaner->name}}</span>
                            </div>
                        
                            <select name="{{ $cleaner->name }}" class="cleanerUnits sterilizeNumberDropdown form-control ">
                                @for ($i = 0; $i <= 30; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        
                        </div>
                    @endforeach()
                </div>

                <div class="form-group mx-4">
                    <label for="comment_form">Comments</label>
                    <textarea class='form-control' name="comment_form" id="comment" rows="2" ></textarea>
                </div>

                <p class="printerStatus text-center">Printer Status : <span>Inactive</span></p>
                <button  class="log d-flex justify-content-center m-auto w-50 btn btn-primary btn-lg mx-" type='submit'>Print</button><br>
            </form> 
            
        </div>

    </div>
    @include('includes.login-modal')


    @section('script')]
        <script src="https://cdn.rawgit.com/kjur/jsrsasign/c057d3447b194fa0a3fdcea110579454898e093d/jsrsasign-all-min.js"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/dependencies/rsvp-3.1.0.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/dependencies/sha-256.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/qz-tray.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/qz-print.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/sterilize.js')}}"></script>

    @endsection
@endsection