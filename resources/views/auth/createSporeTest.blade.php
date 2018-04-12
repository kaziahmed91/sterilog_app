@extends('layouts.app')
@section('content')
@include('includes.navbar')
<div class="container">

    <div class="card m-4 ">
        <div class=" m-4 mb-0 row header  border-bottom">
            <p class='display-4'>Spore Test</p>
        </div>
        <div class="card-body">

            <div class="row m-auto d-flex justify-content-center ">
                <button type="button" name="" id="" class="btn btn-primary btn-lg mr-2">Add Test</button>
                <a name="" id="" class="btn btn-info btn-lg " href="#" role="button">View Logs</a>
            </div>

            <br><br>
            <div class="col-md-6 offset-md-3">


            <div class="card">
                <div class="card-header">Add a Test</div>
                <div class="card-body">
                    <form action="/spore/new" method="post" class=''>
                         {{ csrf_field() }}
                        <div class="row mx-1">
                 

                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-control form-check-input" name="control_sterile" id="control_sterile" value="1" >
                                Control Vial Sterile
                              </label>
                            </div>
                            &nbsp;&nbsp;
                             <div class="form-check">
                              <label class="form-check-label">
                                <input type="checkbox" class="form-control form-check-input" name="test_sterile" id="test_sterile" value="1" >
                                Test Vile Sterile
                              </label>
                            </div>

                        </div>
                        <br>

                        <div class="form-group">
                            <label for="sterilizer">Sterilizer</label>
                            <select class="custom-select"  name="sterilizer" id="sterilizer">
                                <option selected>Select one</option>
                                @foreach( $sterilizers as $sterilizer )
                                    <option value="{{ $sterilizer->sterilizer_name }}">{{ $sterilizer->sterilizer_name }}</option>
                                @endforeach

                            </select>
                            <small id="helpId" class="text-muted hidden">Help text</small>
                        </div>

                        <div class="form-group">
                            <label for="entry_cycle_number" class=''>Cycle Number</label>
                            <input type="number" class="form-control" name="" id="entry_cycle_number" aria-describedby="helpId" placeholder="Cycle Number">
                        </div>

                        <div class="form-group">
                            <label for="lot_number" class=''>Lot Number</label>
                            <input type="number" class="form-control" name="" id="lot_number" aria-describedby="helpId" placeholder="Cycle Number">
                        </div>

                        <div class="form-group">
                            <label for="initial_comments">Comments</label>
                            <textarea class='form-control' name="initial_comments" id="initial_comments" rows="2" ></textarea>
                        </div>
                        
                        <button class="btn btn-submit btn-primary btn-lg" type='submit'>Add</button>
                    </form>
                </div>
            </div>
            </div>



        </div>


    </div>


</div>


@endsection()

