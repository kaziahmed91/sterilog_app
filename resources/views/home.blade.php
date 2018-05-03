@extends('layouts.app')
@section('content')
    {{-- @include("includes.navbar") --}}
    @include("includes.errorbar")
    @include("includes.topbar")

    <div class="container d-flex mt-5 ">
    
    <div class="col-sm-4  d-flex align-items-center">
        <img class='largeLogo' src="{{asset('images/Sterilog_Logo.png')}}" alt="">
    </div>

    <div class="offset-1 col-sm-6  d-flex flex-column">
        <div class="row justify-content-between">
            <a class="menu-button btn btn-primary  d-flex" href="{{ route('sterile') }}">
                <img class="menu-icon" src="/icons/sterilizer_icon.png" alt="">
                <div class=' d-flex flex-column '>
                    <p class='m-auto'>Sterilizer <br>Load</br></p>
                </div>
            </a>
                <br><br>
            <a class="menu-button  btn btn-primary d-flex" href="{{ route('sterile.logs') }}">
                <img class="menu-icon" src="{{ asset('icons/log_icon.png')}}" alt="">
                <div class='d-flex flex-column '>
                    <p class='m-auto'>Sterilizer <br>Logs</p>
                </div>
            </a>
        </div>

            <br><br>

        <div class="row justify-content-between">
            <a class="menu-button btn  btn-primary  d-flex" href="{{ route('spore') }}">
                <img class="menu-icon" src="{{ asset('icons/spore_icon.svg')}}" alt="">
                <div class='d-flex flex-column '>
                    <p class='m-auto'>Spore <br>Test</p>
                </div>
            </a>
                <br><br>
            <a class="menu-button btn btn-primary  d-flex" href="{{ route('spore.logs') }}">
                <img class="menu-icon" src="{{ asset('icons/log_icon.png')}}" alt="">
                <div class="d-flex flex-column ">
                    <p class='m-auto'>Spore <br>Logs</p>
                </div>
            </a>

        </div>
        
    </div>
    

</div>
    @include('includes.login-modal')
@endsection
