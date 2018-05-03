@extends('layouts.app')
@section('content')

<div class="d-flex align-items-center">
	<img  style="width:550px;" src="{{asset('images/Sterilog_Logo.png')}}" alt="">
</div>


<div class="centerContainer">
	<p>User Login</p>
</div>



<div class="passwordDiv">
	<div class="passwordField"> </div>

	<div class="removeBtn">
		<div class="triangle"></div>
		<div class="removeButton">
			<span>&#10006;</span>
		</div>
	</div>
</div>
<br>
<div class="centerContainer ">
	<p class='error hidden'> Invalid Pin! Please try again</p>
</div>

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
	<div class="button">0</div>
</div>

<br>
<div class="centerContainer">
	<button type="submit" id="login" class= "btn btn-primary btn-lg">Login</button>
</div>

	@section('script')  
		<script src="{{asset('js/login.js')}}"></script>
	@endsection


@endsection

