<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('images/Sterilog_Checkmark-cropped.png')}}">

    <title>{{ config('app.name', 'Sterilog') }}</title>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.9/js/all.js" integrity="sha384-8iPTk2s/jMVj81dnzb/iFR2sdA7u06vHJyyLlAd4snFpCl/SnyUjRrbdJsw1pGIl" crossorigin="anonymous"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    


</head>
<body>
    <div id="app">
        @yield('content')
    </div>

    <footer class="footer bg-light mt-5 d-flex align-items-center">
        <div class="col-sm-4 text-center">
            Sterilog Inc. Copyright 2018
        </div>
        <div class="col-sm-4 text-center">
            All Rights Reserved <br>
            Terms of Use
        </div>
        <div class="col-sm-4 text-center">

            Email:  
            <a href="mailto:info@sterilog.ca" target="_top">info@sterilog.ca</a> <br>
            Telephone: <a href="+11234567890">+11234567890</a>

        </div>
    </footer>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    
    @yield('script') 
    <script>
    $(document).ready(function() {
        $('.loginDropdown').select2({
                theme: "bootstrap",
                placeholder: 'Select a Username',
            });

        $('.loginDropdown').on("change", function(e) { 
            var user_id  = $('.loginDropdown option:selected').data('id');
            $('#user_name').val(user_id)
            console.log(user_id);
        });

        $('body').on('click', '.alert-close', function() {
            $(this).parents('.alert').hide();
        });

    });
    </script>
</body>

</html>
