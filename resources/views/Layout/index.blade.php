<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="ipws" content="{{ session('ipserver') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>{{ session('cafeName') ?? '' }} v.1.0.1</title>
    <link rel="icon" type="image/x-icon" href="{{ url('/') }}/assets/img/favicon.ico"/>
    <link href="{{ url('/') }}/assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="{{ url('/') }}/assets/js/loader.js"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ url('/') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/elements/alert.css">
    <link href="{{ url('/') }}/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
    <link href="{{ url('/') }}/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
    <!-- <link href="{{ url('/') }}/assets/css/tables/table-basic.css" rel="stylesheet" type="text/css" /> -->
    <!-- END GLOBAL MANDATORY STYLES -->
    <style>
      .btnTransparent{
        border:none; 
        background:transparent
      }
            /* Chrome, Safari, Edge, Opera */
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }

      /* Firefox */
      input[type=number] {
        -moz-appearance: textfield;
      }
    </style>
  	<!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/datatables.css">
    <link href="{{ url('/') }}/plugins/rowgroup/rowGroup.dataTables.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/custom_dt_html5.css">
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/dt-global_style.css">
    @yield('css-body')
  	<!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    
</head>
<body class="alt-menu sidebar-noneoverflow">
    <!-- BEGIN LOADER -->
    <div id="load_screen"> <div class="loader"> <div class="loader-content">
      <div class="spinner-grow align-self-center"></div>
    </div></div></div>
    <!--  END LOADER -->

    <!--  BEGIN NAVBAR  -->
      @include('Layout.topbar')
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="mx-auto d-none pt-2 spinHotkeys" style="width: 200px;"> 
      <div class="spinner-border" role="status">
        <span style="margin: 2 0 0 0" class="sr-only">Loading...</span>
      </div>
      <!-- <span class="badge mb-2 outline-badge-dark"> Proses... </span> -->
    </div>
    <div class="main-container" id="container">
			<div class="overlay"></div>
      <input type='hidden' id='bukalaci' value="{{url('/open/drawerauth')}}">
      <input type='hidden' id='ping' value="{{url('/cek/printer')}}">
      <input type='hidden' id='board' value="{{url('/order/meja/view')}}">
			<div class="search-overlay"></div>
			<!--  BEGIN CONTENT PART  -->
			<div id="content" class="main-content">
        <div class="layout-px-spacing">
          @yield('content-breadcumb')
          <div class="row layout-top-spacing" id="cancel-row">     
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
              @if(session()->has('error'))
                <div class="alert alert-light-danger mb-4" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                  <b>Kesalahan!</b>
                  <ul>
                  @foreach (session('error') as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                  </ul>
                </div> 
              @endif
              @if(session()->has('success'))
                <div class="alert alert-light-success mb-4" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                  <!-- <b>Kesalahan!</b> -->
                  <ul>
                  @foreach (session('success') as $successMessage)
                    <li>{{ $successMessage }}</li>
                  @endforeach
                  <ul>
                </div> 
              @endif
              @if(session()->has('warning'))
                <div class="alert alert-light-warning mb-4" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                  <b>Peringatan!</b>
                  <ul>
                  @foreach (session('warning') as $warningMessage)
                    <li>{{ $warningMessage }}</li>
                  @endforeach
                  <ul>
                </div> 
              @endif
              @if($errors->any())
                <div class="alert alert-light-danger mb-4" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                  <b>Kesalahan!</b>
                  <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                  </ul>
                </div>
              @endif
              @yield('content-body')
            </div>
          </div>
        </div>
			</div>
      <div class="footer-wrapper">
          <div class="footer-section f-section-1">
              <p class="">Copyright © 2021 <a target="_blank" href="https://www.facebook.com/Ikhwan-Komputer-Elektronika-626624144026593">IkhwanKomputer</a>, All rights reserved.</p>
          </div>
          <div class="footer-section f-section-2">
              <p class="">{{ session('cafeName') }}<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5c1ac3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevrons-left"><polyline points="11 17 6 12 11 7"></polyline><polyline points="18 17 13 12 18 7"></polyline></svg></p>
          </div>
      </div>
      <!--  END CONTENT PART  -->
    </div>

    <div class="modal fade" id="cafeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
          </div>
          <div class="modal-body">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default btn-sm d-none modal-action-cancel" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
            <button type="button" style="min-width: 75px;" class="btn btn-danger btn-sm d-none modal-action-delete font-bold"><span class="fa fa-trash fa-fw"></span>Hapus</button>
            <button type="button" style="min-width: 75px;" class="btn btn-default btn-sm d-none modal-action-ok font-bold" data-dismiss="modal">Ok</button>
            <button type="button" style="min-width: 75px;" class="btn btn-success btn-sm d-none modal-action-save font-bold">Simpan</button>
            <button type="button" style="min-width: 75px;" class="btn btn-sm btn-info d-none modal-action-yes font-bold">Ya</button>
          </div>
        </div>
      </div>
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ url('/') }}/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="{{ url('/') }}/bootstrap/js/popper.min.js"></script>
    <script src="{{ url('/') }}/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="{{ url('/') }}/assets/js/app.js"></script>
    <script src="{{ url('/') }}/js/mousetrap.min.js"></script>
    <script src="{{ url('/') }}/js/cafe.js"></script>
    <script src="{{ url('/') }}/plugins/font-icons/feather/feather.min.js"></script>
    <script src="{{ url('/') }}/plugins/sweetalerts/sweetalert2.min.js"></script>
    <script src="{{ url('/') }}/plugins/flatpickr/flatpickr.js"></script>
    <script src="{{ url('/') }}/plugins/flatpickr/custom-flatpickr.js"></script>
    <script>
      const pMaster = getIPWS();
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
      $(document).ready(function (){
        feather.replace();
      })
    </script>
    <script src="{{ url('/') }}/assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ url('/') }}/plugins/table/datatable/datatables.js"></script>
    <script src="{{ url('/') }}/plugins/rowgroup/dataTables.rowGroup.min.js"></script>
    <script src="{{ url('/') }}/plugins/table/datatable/button-ext/dataTables.buttons.min.js"></script>    
    <script src="{{ url('/') }}/plugins/table/datatable/button-ext/buttons.html5.min.js"></script>
    <script src="{{ url('/') }}/plugins/table/datatable/button-ext/jszip.min.js"></script>  
    <script src="{{ url('/') }}/plugins/table/datatable/button-ext/buttons.print.min.js"></script>    
		@yield('js-body')
</body>
</html>