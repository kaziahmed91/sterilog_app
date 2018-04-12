@extends('layouts.app')

@section('content')
    @include('includes.navbar')
    <div class="container card border h-70 w-40">

        <div class="row header mx-2 my-2 border-bottom">
            <p class='display-4'> Sterilizer Load </p>
        </div>

        <form id="sterilizeForm" class="form-group ">
            {{ csrf_field() }}

            <div class="row m-4  p-4 border">
                <div class="form-group m-auto col-md-6">
                    <label class='col-form-label col-md-4 text-lg-right' for="sterilizer">Sterilizer</label>
                    <select class="custom-select col-md-4 is-invalid"  name="sterilizer" id="sterilizer">
                        <option selected>Select one</option>
                        @foreach( $sterilizers as $sterilizer )
                            <option id="{{ $sterilizer->id }}" data-cycleNum="{{$sterilizer->cycle_number}}" value="{{ $sterilizer->sterilizer_name }}">{{ $sterilizer->sterilizer_name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-flex justify-content-center">
                        <strong>Sterilize Must Be Selected!</strong>
                    </div>
                </div>
                <div class="form-group m-auto row col-md-6">
                    <label for="cycle_number" class='col-md-4  col-form-label text-lg-right'>Cycle Number</label>
                    <input type="number" 
                        class="form-control is-invalid col-md-4"
                        name="" id="cycle_number" aria-describedby="helpId" placeholder="Cycle Number">
                    
                    <div class="invalid-feedback d-flex justify-content-center">
                        <strong>Sterilize Must Be Selected!</strong>
                    </div>
                </div>
            </div>

            <div class="row mx-2 mt-2 cleaners">
                @foreach( $cleaners as $key => $cleaner )
                    <div class="col-md-4 span4 input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="{{ $cleaner->id}}">{{$cleaner->name}}</span>
                        </div>
                        <input type="number" name='{{ $cleaner->name }}' class="form-control" placeholder="Username" aria-label="Username" aria-describedby="cg1">
                    </div>
                @endforeach()
            </div>

            <div class="form-group mx-4">
                <label for="comment_form">Comments</label>
                <textarea class='form-control' name="comment_form" id="comment" rows="2" ></textarea>
            </div>

            <button  class="log d-flex justify-content-center m-auto w-50 btn btn-primary btn-lg mx-" type='submit'>Print</button><br>
        </form> 
        
        <a href="{{ url('sterilize/log') }}" class="mx-auto w-50 mb-5 btn btn-primary btn-lg">View Log</a>



    </div>

    @section('script')]
        <script src="https://cdn.rawgit.com/kjur/jsrsasign/c057d3447b194fa0a3fdcea110579454898e093d/jsrsasign-all-min.js"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/dependencies/rsvp-3.1.0.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/dependencies/sha-256.min.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/dependencies/qz-tray.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/qz-print.js')}}"></script>
        <script type="text/javascript" src="{{asset('js/sterilize.js')}}"></script>
    @endsection
@endsection