@extends('Layout.index-notopbar')

@section('content-body')
<div class="widget-content widget-content-area br-6">
    <div class="row">
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox">
        <div class="widget-header">                                
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>Konfigurasi Awal Aplikasi</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content">
          <form class="needs-validation" method="post" novalidate action="{{ url('/init-setting-setup') }}" enctype="multipart/form-data">
            <div class="form-group mb-2">
              <label for="NamaApp">Nama Cafe</label>
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="text" class="form-control" name="NamaApp" value="{{ old('NamaApp')}}" placeholder="" required>
              <div class="invalid-feedback">
                Nama Cafe harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="Telp">Telp</label>
              <input type="text" class="form-control" name="Telp" id="Telp" value="{{ old('Telp')}}" placeholder="0748-00000" required>
              <div class="invalid-feedback">
                Nomor Telepon harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="KodeInvoice">Prefix Invoice</label>
              <input type="text" class="form-control" name="KodeInvoice" id="KodeInvoice" value="{{ old('KodeInvoice')}}" placeholder="ICR" required>
              <div class="invalid-feedback">
                Prefix Invoice harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="Alamat">Alamat</label>
              <textarea class="form-control" name="Alamat" rows="3" id="Alamat" value="{{ old('Alamat')}}" placeholder="" required></textarea>
              <div class="invalid-feedback">
                Alamat harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="HeaderStruk">Header Struk Pelayan</label>
              <textarea class="form-control" name="HeaderStruk" required>{{ old('HeaderStruk')}}</textarea>
              <div class="invalid-feedback">
                Format Atas Struk harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="FooterStruk">Footer Struk Pelayan</label>
              <textarea class="form-control" name="FooterStruk" required>{{ old('FooterStruk')}}</textarea>
              <div class="invalid-feedback">
                Format Akhir Struk harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="HeaderStruk">Header Struk Kasir</label>
              <textarea class="form-control" name="HeaderStrukKasir" required>{{ old('HeaderStrukKasir')}}</textarea>
              <div class="invalid-feedback">
                Format Atas Struk harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="FooterStruk">Footer Struk Kasir</label>
              <textarea class="form-control" name="FooterStrukKasir" required>{{ old('FooterStrukKasir')}}</textarea>
              <div class="invalid-feedback">
                Format Akhir Struk harus diisi!
              </div>
            </div>
            <!-- <div class="form-group mb-2">
              <label for="namaToko">Alamat</label>
              <input type="text" class="form-control" id="namaToko" placeholder="">
            </div> -->
            <div class="form-group mb-2">
              <label for="IpPrinter">Ip Printer Kasir</label>
              <input type="text" class="form-control" value="{{ old('IpPrinter')}}" name="IpPrinter" id="IpPrinter" placeholder="0.0.0.0" required>
              <div class="invalid-feedback">
                Ip Printer harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="IpServer">Ip Server</label>
              <input type="text" class="form-control" value="{{ old('IpServer')}}" name="IpServer" id="IpServer" placeholder="0.0.0.0" required>
              <div class="invalid-feedback">
                Ip Server harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="IpPrinter">Password Laci</label>
              <input type="password" class="form-control" value="{{ old('PasswordLaci')}}" name="PasswordLaci" id="PasswordLaciform" required>
              <div class="invalid-feedback">
                Password harus diisi!
              </div>
            </div>
            <div class="form-group mb-2">
              <label for="logo">Logo Aplikasi</label>
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="LogoApp" name="file" required>
                <label class="custom-file-label" for="customFile">Choose file</label>
              </div>
              <div class="invalid-feedback">
                Logo harus diisi!
              </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('js-body')
  <script>
    $(document).ready(function (){
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      let forms = document.getElementsByClassName('needs-validation');

      // Loop over them and prevent submission
      let validation = Array.prototype.filter.call(forms, function(form) {
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