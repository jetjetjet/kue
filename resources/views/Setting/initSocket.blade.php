@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Pengaturan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Aplikasi</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/setting') }}">Pengaturan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Notif</a></li>
  </ol>
@endsection

@section('content-body')
<div class="widget-content widget-content-area br-6">
    <div class="row">
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="widget-content">
          <div class="row">
            <div class="col-lg-12">
              <div class="jumbotron">
                <h2 class="display-4 mb-5  mt-4">Konfigurasi Notifikasi</h2>
                <p class="lead mt-3">Halaman ini untuk mengaktifkan notifikasi secara manual, jika notifikasi tidak otomatis aktif.</p>
                <p class="lead mb-4">Status Notifikasi <span id="spinner" class="spinner-border" role="status"></span> <span id="notifstatus" class="badge"></span></p>
                <div id="loader" class="loader dual-loader mx-auto d-none"></div>
                <a class="btn btn-info d-none" id="submit" href="{{url('/setting/start-notif')}}" type="button">Aktifkan</a>
                <button id="loding" class="btn btn-primary d-none" type="button" disabled>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Loading...
                </button>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js-body')
  <script>
    $(document).ready(function (){
      $('#submit').on('click', function(e){
        $('#submit').addClass('d-none')
        $('#loding').removeClass('d-none')
        setTimeout(() => {
          location.reload()
        }, 5000);
      });

      ws.onerror = function(e) { 
        $('#submit').removeClass('d-none')
        $('#spinner').remove()
        $('#notifstatus').html('Error/Tidak Aktif')
        $('#notifstatus').addClass('badge-danger')
      };
      ws.onopen = function(e) {
        $('#spinner').remove()
        $('#notifstatus').html('Aktif')
        $('#notifstatus').addClass('badge-success')
      };
      // let open = ws.onopen(function(){
      //   $('#spinner').remove()
      //   $('#notifstatus').html('Aktif')
      //   $('#notifstatus').addClass('badge-success')
      // });
      
    })
  </script>
@endsection