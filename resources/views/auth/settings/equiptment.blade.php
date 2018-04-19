@extends('layouts.settings')

@section('settings')
    <div class="col-sm-9 border ">
        <p class="settingsHeader border-bottom">Sterilization Equiptment Settings</p>
        @if(count($errors) > 0 )
            <div class="alert alert-danger">
                <strong>Errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(Session::has('success') )
            <div class="alert alert-success">
                <strong>Success:</strong>
                {{Session::get('success')}}
            </div>
        @endif
        @if(Session::has('error') )
            <div class="alert alert-danger">
                <strong>Error:</strong>
                {{Session::get('error')}}
            </div>
        @endif
        <div id="accordion" class="my-4">
            
            <div class="card">
                <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        View Sterilizers
                    </button>
                </h5>
            </div>
            
            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($equiptments as $eqpt)
                            <li class='list-group-item d-flex align-items'>
                                <span class="col-sm-4">
                                    <strong>Name: </strong>{{ $eqpt['sterilizer_name'] }} 
                                </span>
   
                                <span class="col-sm-4"><strong>Date Added</strong>
                                    {{Carbon\Carbon::parse($eqpt['date_added'])->format('d-m-Y ')}}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Add Sterilization Equiptment
                        </button>
                    </h5>
                </div>
            
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <form action="/settings/register/equiptment" method="post">
                            {{ csrf_field() }}

                            <div class="form-group ">
                                <label for="sterilizer_name" id="sterilizer_name">Name</label>
                                <input type="text" name='sterilizer_name' class='form-control '>
                            </div>

                            <div class="form-group ">
                                <label for="manufacturer" id="manufacturer">Manufacturer</label>
                                <input type="text" name="manufacturer" class='form-control '>
                            </div>

                            <div class="form-group ">
                                <label for="serial" id="serial">Serial Number</label>
                                <input type="text" name="serial" class='form-control '>
                            </div>
                        <button class="btn mx-auto btn-submit btn-primary" type='submit'>Register</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Edit Sterilization Equiptment
                        </button>
                    </h5>
                </div>
            
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <form action="/settings/register/equiptment" method="post">
                            {{ csrf_field() }}

                            <div class="form-group ">
                                <label for="sterilizer_name" id="sterilizer_name">Name</label>
                                <input type="text" name='sterilizer_name' class='form-control '>
                            </div>

                            <div class="form-group ">
                                <label for="manufacturer" id="manufacturer">Manufacturer</label>
                                <input type="text" name="manufacturer" class='form-control '>
                            </div>

                            <div class="form-group ">
                                <label for="serial" id="serial">Serial Number</label>
                                <input type="text" name="serial" class='form-control '>
                            </div>
                        <button class="btn mx-auto btn-submit btn-primary" type='submit'>Register</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection