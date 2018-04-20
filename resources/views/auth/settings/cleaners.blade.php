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
                    <ul class="list-group list-group-flush">
                        @foreach($cleaners as $eqpt)
                            <li class='list-group-item d-flex align-items'>
                                <span class="col-sm-4">
                                    <strong>Name: </strong>{{ $eqpt['name'] }} 
                                </span>
   
                                <span class="col-sm-4"><strong>Date Added</strong>
                                    {{Carbon\Carbon::parse($eqpt['created_at'])->format('d-m-Y ')}}
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
                        Add Cleaning Equiptment
                    </button>
                </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <form action="{{route('settings.cleaners.register')}}" method="post">
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
