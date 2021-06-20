@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Pengaturan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Aplikasi</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/setting') }}">Pengaturan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Ubah Pengaturan</a></li>
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
              <h4>Ubah Pengaturan</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content">
          <form class="needs-validation" method="post" novalidate action="{{ url('/setting/simpan') }}" enctype="multipart/form-data">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
            <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
            <div class="form-row">
              <div class="col-md-12 mb-5">
                <input type="text" name="settingcategory" value="{{ old('settingcategory', $data->settingcategory) }}" class="form-control"  placeholder="Nama Pelanggan" required readonly>
              </div>
              @if($data->settingcategory == 'AppLogo')
              <div class="col-md-6 mb-2">
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="LogoApp" name="file" required>
                  <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
              </div>
              <div class="col-md-6 mb-2">                
                <label for="img"><b>Logo saat ini.</b></label>
                <br>
                <img width="150" height="150" src="{{ asset($data->settingvalue) }}" style="vertical-align:top"></img>
                <input type="hidden" name="settingvalue" value="{{ old('settingvalue', $data->settingvalue) }}" />
                <input type="hidden" name="settingkey" value="{{ old('settingkey', $data->settingkey) }}" />           
              </div>
              @else
              <div class="col-md-6 mb-5">
                  <input type="text" name="settingkey" value="{{ old('settingkey', $data->settingkey) }}" class="form-control" readonly  placeholder="Nomor Kontak Pelanggan">
              </div>
              <div class="col-md-12 mb-5">
                  <textarea rows="3" name="settingvalue" class="form-control"  placeholder="Pengaturan saat ini">{{ old('settingvalue', $data->settingvalue) }}</textarea>
              </div>
              @endif
            </div>
            <div class="float-right">
              <a href="{{ url('/setting') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
              <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js-form')
<script>
  
  $(document).ready(function (){
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }else if (form.checkValidity() === true){
        $('#sub').attr('disabled', true);
        }
        form.classList.add('was-validated');
      }, false);
    });
  })
</script>
@endsection