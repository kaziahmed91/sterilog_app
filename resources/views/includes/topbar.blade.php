<div class="card container flex-row p-3" style="border:none">
    
    

<div class="col-sm-8 topbar-buttons">
@if(Route::current()->getName() == 'sterile' || Route::current()->getName() == 'sterile.logs') 
    <a class="btn btn-success btn-lg mr-3" href="{{route('spore')}}">
        <img class="topbar-icon" src="{{ asset('icons/spore_icon.svg')}}" alt="">
        Spore Test</a>
    <a class="btn btn-success btn-lg" href="{{route('spore.logs')}}">
        <img class="topbar-icon" src="{{ asset('icons/log_icon.png')}}" alt="">
        Spore Log</a>
@endif
    
@if( Route::current()->getName() == 'spore' || Route::current()->getName() == 'spore.logs' ) 
    <a class="btn btn-success btn-lg mr-3" href="{{route('sterile')}}">
        <img class="topbar-icon" src="{{ asset('icons/sterilizer_icon.png')}}" alt="">
        Sterilize </a>
    <a class="btn btn-success btn-lg" href="{{route('sterile.logs')}}">
        <img class="topbar-icon" src="{{ asset('icons/log_icon.png')}}" alt="">
        Sterilize Log</a>
@endif
</div>


    <div class="col-sm-4  ">
        @if(Session::has('softUser_fullName'))
        <div class="d-flex align-items-center  justify-content-between">
            Currently: Logged in :<strong>{{ Session::get('softUser_fullName') }}</strong>
            <a href="{{ route('user.logout') }}"> 
                <button class="btn btn-lg  btn-outline-warning"  value="">Logout
                </button>
            </a>
        </div>
        @endif 
        @if(!Session::has('softUser_fullName'))
        <div class="d-flex justify-content-end">
            <button name="" data-toggle="modal" data-target="#loginModal" class="btn btn-lg  btn-outline-success" type="button" value="">Login</button>
        </div>
        @endif
        
    </div>
</div>

