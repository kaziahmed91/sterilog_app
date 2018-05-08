@extends('layouts.settings')

@section('settings')
    <div class="col-sm-9 border ">
        <p class="settingsHeader border-bottom">User Settings for  <span class="font-weight-bold">{{ $user->first_name.' '.$user->last_name }}</p>
        @include('includes.settingsError')


        <div id="accordion" class="my-4">
            <div class="card">
                    <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Edit details
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse " aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body"> 
                        <form action="{{ route('settings.user.edit', ['id' => $user->id] ) }}" method="POST">
                                {{ csrf_field() }}
                                
                                <div class="form-group ">
                                    <label for="first_name">First Name</label>
                                    <input type="text" name="first_name" class='form-control' value ='{{ $user->first_name }}'>
                                </div>
    
                                <div class="form-group ">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" name="last_name" class='form-control' value ='{{ $user->last_name }}'>
                                </div>
    
                                <div class="form-group ">
                                    <label for="user_name">User Name</label>
                                    <input type="text" name="user_name" class='form-control' value ='{{ $user->user_name }}'>
                                </div>

                                <div class="form-group ">
                                    <label for="system_password">System Password</label>
                                    <input type="password" name="system_password" class='form-control '>
                                </div>

                                <button class="btn mx-auto btn-submit btn-primary" type='submit'>Save</button>
                            </form>
                    </div>
                </div>
            </div>

                <div class="card">
                    <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Change password </span> 
                        </button>
                    </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <form action="{{ route('settings.user.password', ['id'=> $user->id]) }}" method="POST">
                                {{ csrf_field() }}
                                
                                <div class="form-group ">
                                    <label for="system_password">System Password</label>
                                    <input type="password" name="system_password" class='form-control '>
                                </div>
    
                                <div class="form-group ">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name="new_password" class='form-control '>
                                </div>
    
                                <div class="form-group ">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input type="password" name="confirm_password" class='form-control '>
                                </div>
    
    
                                
                                <button class="btn mx-auto btn-submit btn-primary" type='submit'>Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
        
        </div>
    </div>

@endsection