@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Pelanggan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/cust') }}">Pelanggan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Pelanggan</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <div class="row">
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox widget box box-shadow">
        <div class="widget-header">                                
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Pelanggan</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{ url('/cust/simpan') }}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-6 mb-5">
                <label for="numbe">Nama Pelanggan</label>
                <input type="text" name="custname" value="{{ old('custname', $data->custname) }}" class="form-control"  placeholder="Nama Pelanggan" required {{ $data->id == null ? '' : 'readonly' }}>
              </div>
              <div class="col-md-6 mb-5">
                  <label for="floo">Nomor Kontak Pelanggan</label>
                  <input type="text" name="custphone" value="{{ old('custphone', $data->custphone) }}" class="form-control"  placeholder="Nomor Kontak Pelanggan">
              </div>
              <div class="col-md-12 mb-5">
                  <label for="floo">Alamat Pelanggan</label>
                  <textarea rows="3" name="custaddress" class="form-control"  placeholder="Alamat Pelanggan">{{ old('custaddress', $data->custaddress) }}</textarea>
              </div>
            </div>
            <div class="float-right">
              <a href="{{ url('/cust') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
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