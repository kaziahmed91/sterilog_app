@extends('layouts.settings')

@section('settings')
    <div class="col-sm-9 border ">
        <p class="settingsHeader border-bottom">Cleaner Settings</p>
            
        <div id="accordion" class="my-4">
            
            <div class="card">
                <div class="card-header" id="headingOne">
                <h5 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        View Cleaners
                    </button>
                </h5>
            </div>
            
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        @foreach($cleaners as $eqpt)
                            <li>{{ $eqpt['name'] }}</li>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Add Cleaning Equiptment
                    </button>
                </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <form action="/settings/register/cleaner" method="post">
                            {{ csrf_field() }}

                            <div class="form-group ">
                                <label for="cleaner_name">Cleaner Name</label>
                                <input type="text" name='cleaner_name' class='form-control '>
                            </div>
                            
                            <button class="btn mx-auto btn-submit btn-primary" type='submit'>Submit</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
