@extends('layouts.settings')

@section('settings')
    <div class="col-sm-9 border ">
        <p class="settingsHeader border-bottom">Company Settings</p>
            
        <div id="accordion" class="my-4">

            <div class="card">
                <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Edit Company Information
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