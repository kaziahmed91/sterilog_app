@extends('layouts.settings')

@section('settings')
    <div class="col-sm-9 border ">
        <p class="settingsHeader border-bottom">User Settings</p>


        <div id="accordion" class="my-4">
            
            <div class="card">
                <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        View Users
                    </button>
                </h5>
            </div>
            
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($users as $user)
                                <li class='list-group-item d-flex align-items'>
                                    <span class="col-sm-3">
                                        <strong>Name: </strong>{{ $user->first_name }} {{ $user->last_name }} 
                                    </span>
                                    <span class="col-sm-3">
                                        <strong>User Id: </strong> {{ $user->user_name }}
                                    </span>
                                    <span class="col-sm-4"><strong>Date Added</strong>
                                        {{Carbon\Carbon::parse($user['entry_at'])->format('d-m-Y ')}}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Add a User
                    </button>
                </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <form action="{{ route('settings.user.register') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group ">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" class='form-control '>
                            </div>

                            <div class="form-group ">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" class='form-control '>
                            </div>

                            <div class="form-group ">
                                <label for="user_name">User Name</label>
                                <input type="text" name="user_name" class='form-control '>
                            </div>

                            <div class="form-group ">
                                <label for="password">Password</label>
                                <input type="number" name='password' class='form-control '>
                            </div>
                            
                            <button class="btn mx-auto btn-submit btn-primary" type='submit'>Submit</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" id="headingThre">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Forgotten Password
                    </button>
                </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                        <form action="{{ route('settings.user.password') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="user_name">Select User</label><br>
                                <select class="userNames" name="user_id">
                                    @foreach($users as $user)
                                        <option class=""  value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option> 
                                    @endforeach
                                </select>
                            </div>
                   
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