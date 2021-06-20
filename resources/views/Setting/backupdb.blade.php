@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Backup Database</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/setting/') }}">Aplikasi</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Backup Database</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <div class="row">
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox">
        <div class="widget-header">                                
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>Backup DB</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content">
          <form class="needs-validation" method="get" novalidate action="{{ url('/setting/backupdb') }}">
            <div class="form-row">
              <div class="media">
                <div class="col-md-12 mb-2">
                  <p class="media-text">Halaman ini untuk backup database aplikasi secara Manual.</p>
                </div>
              </div>
              <div class="col-md-12 mb-2">
                <label>Konfirmasi</label>
                <br>
                <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                  <input type="checkbox" name="proses">
                  <!-- <span class="slider"></span> -->
                  <span class="slider round"></span>
                </label>
              </div> 
            </div>    
            <div class="float-right">
              <a href="{{ url('/setting') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
              @if(Perm::can(['pengaturan_backupdb']))
                <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js-form')
<script>
  
</script>
@endsection