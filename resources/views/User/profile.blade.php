@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>User</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/user') }}">User</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Profil</a></li>
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
              <h4>Profil User</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content">
          <form class="needs-validation" method="post" novalidate action="{{ url('/profile/simpan') . '/' . session('userid') }}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-6 mb-4">
                <label for="username">Username</label>
                <input type="text" name="username" value="{{ old('username', $data->username) }}" class="form-control" id="username" placeholder="Username" readonly>
              </div>
              <div class="col-md-6 mb-4">
                <label for="userjoindate">Tanggal Awal Kerja</label>
                <input type="text" name="userjoindate" value="{{ old('userjoindate', \carbon\carbon::parse($data->userjoindate)->format('d-m-Y')) }}" class="form-control" readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-6 mb-4">
                <label for="userfullname">Nama Lengkap</label>
                <input type="text" name="userfullname" value="{{ old('userfullname', $data->userfullname) }}" class="form-control" id="userfullname" placeholder="Nama Lengkap">
              </div>
              <div class="col-md-6 mb-4">
                <label for="usercontact">Telp/Hp</label>
                <input type="text" name="usercontact" value="{{ old('usercontact', $data->usercontact) }}" class="form-control" id="usercontact" placeholder="Kontak Telp/Hp">
              </div>
              <div class="col-md-12 mb-4">
                <label for="useraddress">Alamat</label>
                <textarea class="form-control" name="useraddress" >{{ old('useraddress', $data->useraddress) }}</textarea>
              </div>
            </div>
            <div class="float-left">
              <button class="btn btn-primary mt-2" id="passbutt" type="button">Ganti Kata Sandi</button>
            </div>
            <div class="float-right">
              <a href="{{ url('/user') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
              <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="popPass" class="d-none">
    <div class="form-horizontal">
      <div class="form-group required">
        <label for="nama">Password</label>
        <input type="text" id="pass" name="userpassword" class="form-control" required>
      </div>
    </div>
  </div>
@endsection

@section('js-form')
<script>
  $(document).ready(function (){
    
    const toast = swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      padding: '2em'
    });

    $('#passbutt').click(function(){
      var modal = showPopupForm(
        $(this),
        { btnType: 'primary', keepOpen: true },
        'Ubah Password',
        $('#popPass'),
        '{{ url("profile/ubah-password") }}' + '/' + $('#id').val(),
        function ($form){
            return {
              userpassword: $form.find('[name=userpassword]').val()
            };
        },
        //callback
        function (data){
          toast({
            type: data['status'],
            title: data['messages'][0],
            padding: '2em',
          })
        });
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