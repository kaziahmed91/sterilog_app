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
            
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
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
                                    <a href="{{route('settings.user', ['id' => $user->id] )}}" class="col-sm-2 " style="color: #3097D1">Edit</a> 
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

        </div>
    </div>

@endsection