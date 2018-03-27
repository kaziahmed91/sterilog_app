@extends('layouts.app')
@include('includes.navbar')

@section('content')

    <div class="container card border h-70 w-40">
        <div class="row header mx-2 my-2 border-bottom">
            <p class='display-4'> Sterilizer Log </p>
        </div>
        <table class="table ">
            <thead class="thead-light">
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Time</th>
                    <th scope="col">Operator</th>
                    <th scope="col">Sterilizer</th>
                    <th scope="col">Cycle</th>
                    <th scope="col">Package</th>
                    <th scope="col"># of Packages</th>
                    <th scope="col">Type 1 Status</th>
                    <th scope="col">Type 4 Status</th>
                    <th scope="col">Type 5 Status</th>
                    <th scope="col">Comment</th>

                </tr>
            </thead>
        </table>
    </div>


@endsection