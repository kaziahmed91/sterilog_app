@extends('layouts.app')
@section('content')
    @include('includes.navbar')

    <div class="container border  w-70 p-4">

        <div class=" m-4 row header  border-bottom">
            <p class='display-4'>Settings</p>
        </div>

        <div class="row mx-4">

            <ul class="list-group col-sm-3 settingsNav">
                <a class="list-group-item pointer
                    {{ $route === "user" ? "active currentPage" : "" }}" 
                   href="{{ route('settings.user') }}">User</a>
                   
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
    
    @section('script')
        <script type="text/javascript" src="{{ asset('js/settings_page.js') }}"></script>
    @endsection

@endsection



