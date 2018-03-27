@extends('layouts.app')


@section('content')
    <div class="container ">
        <div class="row justify-content-md-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Company Register</div>
                    <div class="card-body">
                        <form action="{{ url('company_register') }}" method="post" >
                            {{ csrf_field() }}

                            <div class="form-group row">
                                <label class="col-lg-4 form-label text-lg-right col-form-label" for="name">Company Name</label>
                                <div class="col-lg-6">
                                    <input type="text" name="name" id="name" 
                                        class="form-control 
                                        {{ $errors->has('name') ? ' is-invalid' : '' }}" 
                                        placeholder="" aria-describedby="helpId" value="{{ old('name')}}">
    
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </div>
                                    @endif
                                </div> 
                            </div>

                            <div class="form-group row">
                              <label for="key_contact" class='col-lg-4 text-lg-right col-form-label'>Key Contact Name</label>
                            <div class="col-lg-6">
                                <input type="text" class="form-control
                                    {{ $errors->has('key_contact') ? ' is-invalid' : '' }}" 
                                    name="key_contact" id="key_contact" aria-describedby="helpId" placeholder=""
                                >
                                
                                @if ($errors->has('key_contact'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('key_contact') }}</strong>
                                    </div>
                                @endif

                            </div>
                            </div>

                            <div class=" form-group row">
                                <label class="col-lg-4 text-lg-right col-form-label" for="address">Address</label>
                                <div class="col-lg-6">
                                    <input type="text" name="address" id="address" class="form-control
                                        {{ $errors->has('address') ? ' is-invalid' : '' }}" 
                                        placeholder="" aria-describedby="helpId" value="{{ old('address')}}"
                                    >
                                        @if ($errors->has('address'))
                                            <div class="invalid-feedback">
                                                <strong>{{ $errors->first('address') }}</strong>
                                            </div>
                                        @endif
                                </div>
                            </div>

                                <div class="form-group row ">
                                        <label for="province" class="col-lg-4 text-lg-right col-form-label">Province</label>
                                        <div class="col-lg-6">
                                            <select class="form-control
                                                {{ $errors->has('province') ? ' is-invalid' : '' }}" 
                                                name="province" id="province " value="{{ old('province')}}"
                                            >
                                                <option value=""></option>
                                                @foreach($provinces as $province)
                                                <option value="{{$province}}">{{$province}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('province'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('province') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                </div>
                                
                                <div class="form-group row ">
                                    {{--  <div class="">  --}}
                                        <label for="postal" class="col-lg-4 text-lg-right col-form-label">Postal Code</label>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control
                                            {{ $errors->has('postal') ? ' is-invalid' : '' }}" 
                                             name="postal" id="postal" aria-describedby="helpId" placeholder="" value="{{ old('postal')}}"
                                            >
                                            @if ($errors->has('postal'))
                                                <div class="invalid-feedback">
                                                    <strong>{{ $errors->first('postal') }}</strong>
                                                </div>
                                            @endif
                                        </div>
                                    {{--  </div>  --}}
                                </div>  
                                
                            {{--  </div>  --}}

                            <div class="form-group row">
                              <label for="telephone" class="col-lg-4 text-lg-right col-form-label">Telephone</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control
                                        {{ $errors->has('telephone') ? ' is-invalid' : '' }}" 
                                        name="telephone" id="telephone" aria-describedby="helpId" placeholder="" value="{{ old('telephone')}}">
                                    @if ($errors->has('telephone'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('telephone') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            

                            <div class="form-group row">
                                <label for="email" class="text-lg-right col-lg-4">Email </label>
                                <div class="col-lg-6">
                                    <input type="email" class="form-control
                                        {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                        name="email" id="email" aria-describedby="emailHelpId" placeholder="" value="{{ old('email')}}">
                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="offset-lg-4 col-lg-6 submit">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @section('script')
        <script type="text/javascript" >
            // $(document).ready(function(){
            //     $('.submit').click(function(e) {
            //         e.preventDefault();
            //     })
            // })
        
        </script>
    @endsection
@endsection