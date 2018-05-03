@extends('layouts.app')
@section('content')
    {{-- @include('includes.navbar')  --}}
    {{-- @include('includes.errorbar') --}}
    {{-- @include('includes.topbar') --}}


<br><br>

<div class="centerContainer">
	<p>User Password</p>
</div>

 
<div class="passwordField">
	<span class="numberInput"></span>
	<span class="numberInput"></span>
	<span class="numberInput"></span>
	<span class="numberInput"></span>
	<span class="numberInput"></span>
	<span class="numberInput"></span>
</div>



<br>

<div class="buttons">
	<div class="button">1</div>
	<div class="button">2</div>
	<div class="button">3</div>
	<div class="button">4</div>
	<div class="button">5</div>
	<div class="button">6</div>
	<div class="button">7</div>
	<div class="button">8</div>
	<div class="button">9</div>
	{{-- <div class="button">&larr;</div> --}}
	<div class="button">0</div>
	{{-- <div class="button">&rarr;</div> --}}
</div>

<br>
<div class="centerContainer">
	<button type="submit" class= "btn btn-primary btn-lg">Login</button>
</div>

	@section('script')  
		<script src="{{asset('js/login.js')}}"></script>
	@endsection


@endsection

