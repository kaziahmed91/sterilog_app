@extends('layouts.app')
@section('content')
    {{-- @include("includes.navbar") --}}
    {{-- @include("includes.errorbar") --}}
    {{-- @include("includes.topbar") --}}

    <div class="container  mt-5">
        <div class="btn-group d-flex justify-content-end">

            <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                System
            </button>

            <div class="dropdown-menu">
                <a href="{{route('settings.users')}}" class="dropdown-item"> Settings</a>
                <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                </div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                        style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
        </div>
    </div>
    
    <div class="container">

        <div class="d-flex mt-5">

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
    </div>

@endsection
