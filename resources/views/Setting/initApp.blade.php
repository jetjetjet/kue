@extends('Layout.index-notopbar')

@section('content-body')
<div class="widget-content widget-content-area br-6">
    <div class="row">
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="widget-content">
          <div class="row">
            <div class="col-lg-12">
              <div class="jumbotron">
                <h2 class="display-4 mb-5  mt-4">Konfigurasi Awal Aplikasi</h2>
                <p class="lead mt-3 mb-4">Halaman ini adalah konfigurasi awal sebelum aplikasi dapat digunakan. Klik selanjutnya untuk memulai pengaturan aplikasi.</p>
                  <div id="loader" class="loader dual-loader mx-auto d-none"></div>
                  <a class="btn btn-info" id="submit" href="{{url('/init-app')}}" role="button">Selanjutnya</a>
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
      $('#submit').on('click', function(){
        $('#loader').removeClass('d-none')
      })

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