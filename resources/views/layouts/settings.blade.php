@extends('layouts.app')
@section('content')

@include('includes.topbar')
    <div class=" border-top border-bottom p-2 mb-3">
        <h1 class="text-lg-center">Settings</h1>
    </div>
    <div class="container ">

        <div class="row mx-4">

            <ul class="list-group col-sm-3 settingsNav">
                <a class="list-group-item pointer
                    {{ $route === "user" ? "active currentPage" : "" }}" 
                   href="{{ route('settings.users') }}">User</a>
                   
                <a class="list-group-item pointer
                    {{ $route === "equiptment" ? "active currentPage" : "" }}" 
                     href="{{ route('settings.equiptment') }}">Equiptment</a>

                <a class="list-group-item pointer
                   {{ $route === "cleaners" ? "active currentPage" : "" }}" 
                     href="{{ route('settings.cleaners') }}">Cleaners</a>

                <a class="list-group-item pointer
                    {{ $route === "company" ? "active currentPage" : "" }}" 
                     href="{{ route('settings.company') }}">Company</a>
            </ul>

            @yield('settings')

        </div>

    </div>

    <style>
        body {
            overflow: auto;
        }
    </style>
    
    @section('script')
        <script type="text/javascript" src="{{ asset('js/settings_page.js') }}"></script>
    @endsection

@endsection



