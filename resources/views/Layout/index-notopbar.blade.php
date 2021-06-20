<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{ session('cafeName') ?? '' }} v.1.0.1</title>
    <link rel="icon" type="image/x-icon" href="{{ url('/') }}/assets/img/favicon.ico"/>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ url('/') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/elements/alert.css">
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/dt-global_style.css">
    <link href="{{ url('/') }}/plugins/loaders/custom-loader.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/select2/select2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  
    <style>
    /* SELECT2 MODAL */
    .select2-close-mask{
      z-index: 2099;
    }
    .select2-dropdown{
      z-index: 3051;
    }

    .container .jumbotron {
      box-shadow: 0 0 50px #3F0C1F;
      border: 2px solid #3F0C1F;
    }

    /* MENU CATEGORIES PAGE */
    .category-tile { 
      position: relative;
      border: 1px solid #3F0C1F;
      overflow: hidden;
      width: 120px; /*automatically center image: give width, and margin left/right to auto */
      height: 120px;
      margin: 5px 5px 5px;
    }
    .category-tile span {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 100%;
      text-align: center;
      text-transform: uppercase;
      background-color: #000;
      color: #fff;
      opacity: .8;
    }

    .imgrespo {
      height: 30vw;
      width: auto;
      min-height: 55px;
      max-height: 295px;
    }

/* END MENU CATEGORIES PAGE */
  </style>
</head>
<body class="sidebar-noneoverflow" data-spy="scroll" data-target="#navSection" data-offset="100">
  <div class="mx-auto d-none pt-2 spinHotkeys" style="width: 200px;"> 
    <div class="spinner-border" role="status">
      <span style="margin: 2 0 0 0" class="sr-only">Loading...</span>
    </div>
    <!-- <span class="badge mb-2 outline-badge-dark"> Proses... </span> -->
  </div>
  <div class="main-container" id="container">
    <input type='hidden' id='bukalaci' value="{{url('/open/drawerauth')}}">
    <input type='hidden' id='ping' value="{{url('/cek/printer')}}">
    <input type='hidden' id='board' value="{{url('/order/meja/view')}}">
    <div id="content" class="main-content">
      <div class="layout-px-spacing">
        @yield('content-breadcumb')
        <div class="row layout-top-spacing" id="cancel-row">     
          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            @if(session()->has('error') || $errors->any())
              <div class="alert alert-light-danger mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                <b>Kesalahan!</b>
                <ul>
                @if($errors->any())
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                @else
                  @foreach (session('error') as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                @endif
                </ul>
              </div> 
            @endif
            @yield('content-body')
          </div>
        </div>
      </div>
    </div>
  </div>
    
  <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
  <script src="{{ url('/') }}/assets/js/libs/jquery-3.1.1.min.js"></script>
  <script src="{{ url('/') }}/bootstrap/js/popper.min.js"></script>
  <script src="{{ url('/') }}/bootstrap/js/bootstrap.min.js"></script>
  <script src="{{ url('/') }}/plugins/select2/select2.min.js"></script>
  <script src="{{ url('/') }}/plugins/select2/custom-select2.js"></script>
  <script src="{{ url('/') }}/assets/js/custom.js"></script>
  <script src="{{ url('/') }}/assets/js/app.js"></script>
  <script src="{{ url('/') }}/js/mousetrap.min.js"></script>
  <script src="{{ url('/') }}/js/cafe.js"></script>
  <script src="{{ url('/') }}/assets/js/forms/bootstrap_validation/bs_validation_script.js"></script>
  <script src="{{ url('/') }}/plugins/sweetalerts/sweetalert2.min.js"></script>
  <script>
      const pMaster = "{{ session('ipserver') }}";
      let ws = new WebSocket('ws://'+ pMaster +':8910/kapews');
      ws.onopen = function(e) {
        $('#notiferror').addClass('d-none')
        localStorage.setItem("notif", "1");
        ws.send('Ok')
      }
      ws.onerror = function(e) {
        localStorage.removeItem("notif");
        $('#notiferror').removeClass('d-none')
      }
    </script>
  @yield('js-body')
</body>
</html>