@extends('layouts.app')
@include('includes.navbar')
@section('content')

    <div class="container border w-70 p-4">

        <div class=" m-4 row header  border-bottom">
            <p class='display-4'>Settings</p>
        </div>

        <div class="row d-flex m-4 justify-content-center ">
            <div class="p-2 createUserBtn">
                <i href="{{asset('icons/add_user.svg')}}"></i>
                <button class="btn btn-lg btn-block  btn-outline-primary">Create User</button>
            </div>

            <div class="p-2 addEqptBtn">
                <i href="{{asset('icons/add_user.svg')}}"></i>
                <button class="btn btn-lg btn-block  btn-outline-primary">Add Equiptment</button>
            </div>

            <div class="p-2 viewUsersBtn">
                <i href="{{asset('icons/add_user.svg')}}"></i>
                <button class="btn btn-lg btn-block  btn-outline-primary">View Users</button>
            </div>

        </div>

        <div class="card w-70 mx-5 createUserForm hidden">
            <div class="card-header display-5">
                Create a New User
            </div>

            <div class="card-body m-4">

                <form action="" method="post">
                    {{ csrf_field() }}

                    <div class="form-group ">
                        <label for="first_name">First Name</label>
                        <input type="text" class='form-control '>
                    </div>

                    <div class="form-group ">
                        <label for="last_name">Last Name</label>
                        <input type="text" class='form-control '>
                    </div>

                    <div class="form-group ">
                        <label for="email">Email</label>
                        <input type="text" class='form-control '>
                    </div>
                    
                    <button class="btn mx-auto btn-submit btn-primary" type='submit'>Submit</button>
                </form>
            </div>
        </div>

        <div class="card w-70 mx-5 hidden addEqptForm">
            <div class="card-header display-5">
                Add a Sterilization Equiptment
            </div>
            <div class="card-body m-4">
                <form action="/registerEquiptment" method="post">
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
        
        <div class="card w-70 mx-5 viewUsers hidden">
            <div class="card-header display-5">
                View Users
            </div>
            <div class="card-body m-4">
                <ul>
                    @foreach($users as $user)
                        <li>{{ $user->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>


    </div>
    
    @section('script')
        <script type="text/javascript" src="{{ asset('js/settings_page.js') }}"></script>
    @endsection

@endsection



