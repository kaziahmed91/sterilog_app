@extends('layouts.app')
@include('includes.navbar')

@section('content')
    <div class="container card border h-70 w-40">

        <div class="row header mx-2 my-2 border-bottom">
            <p class='display-4'> Sterilizer Load </p>
        </div>


        <div class="row m-4 p-4 border">
            <div class="form-group m-auto col-md-6">
                <label class='col-form-label col-md-4 text-lg-right' for="Sterilizer">Sterilizer</label>
                <select class="custom-select col-md-4"  name="sterilizer" id="sterilizer">
                    <option selected>Select one</option>
                    <option value=""></option>
                </select>
            </div>
            <div class="form-group m-auto row col-md-6">
              <label for="cycle" class='col-md-4  col-form-label text-lg-right'>Cycle Number</label>
              <input type="number" class="form-control  col-md-4" name="" id="" aria-describedby="helpId" placeholder="Cycle Number">
              {{--  <small id="helpId" class="form-text text-muted">Help text</small>  --}}
            </div>
        </div>

        <form class="form-group " method='post' action='/sterilize'>
            {{ csrf_field() }}
                        
            <div class="row mx-2 mt-2">
                @foreach( $equiptment as $key => $equpt )
                    <div class="col-md-4 span4 input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="cg-1 ">{{$equpt->sterilizer_name}}</span>
                        </div>
                        <input type="number" name='{{ $equpt->sterilizer_name }}' class="form-control" placeholder="Username" aria-label="Username" aria-describedby="cg1">
                    </div>
                
                @endforeach()
            </div>

            <div class="form-group mx-4">
                <label for="comment_form">Comments</label>
                <textarea class='form-control' name="comment_form" id="comment_form" rows="2" ></textarea>
            </div>

            <button  class="d-flex justify-content-center m-auto w-50 btn btn-primary btn-lg mx-" type='submit'>Print</button><br>
        </form> 
        
        <button href="{{ url('sterilize/log') }}" class="mx-auto w-50 mb-5 btn btn-primary btn-lg">View Log</button>



    </div>
@endsection