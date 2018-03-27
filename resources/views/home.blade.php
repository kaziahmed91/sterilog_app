@extends('layouts.app')
@include("includes.navbar")
@section('content')
<div class="container">
    <div class="row mt-5">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
        
                    <br>
                    <div class="container">
                        <a class="btn btn-primary" href="{{ route('sterile') }}">Sterilizer Load</a>
                        <a class="btn btn-primary" href="{{ route('spore') }}">Spore Test</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
