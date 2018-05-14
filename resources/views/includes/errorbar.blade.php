
<div class="alert alert-danger {{ count($errors) > 0 ? '' : 'hidden'  }} fullWidthAlert" role="alert">
    <button type="button"  class="close alert-close" aria-label="Close"><span aria-hidden="true">×</span></button>

    <div class="m-auto d-flex justify-content-center">
        <strong>Errors:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach
        </ul>
    </div>
</div>


<div class="successMsg m-auto  alert alert-success {{ Session::has('success') ? '' : 'hidden' }} fullWidthAlert" role="alert">
    <button type="button" style="font-size: 50px;" class="close alert-close" aria-label="Close"><span aria-hidden="true">×</span></button>

    <div class=' m-auto d-flex justify-content-center'>
        <strong>Success:</strong>
        <ul class=''>
            {{Session::get('success')}}
        </ul>
    </div>
</div>
</div>


<div class="errorMsg alert alert-danger {{ Session::has('error') ? '' : 'hidden' }} fullWidthAlert " role="alert">
    <button type="button" style="font-size: 50px;" class="close alert-close" aria-label="Close"><span aria-hidden="true">×</span></button>

    <div class="m-auto d-flex justify-content-center">
        <strong>Errors:</strong> 
        <ul>
            {{Session::get('error')}}
        </ul>

    </div>
</div>
