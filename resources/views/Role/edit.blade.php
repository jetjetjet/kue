@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Jabatan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/jabatan') }}">Jabatan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Jabatan</a></li>
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
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Jabatan</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content">
          <form class="needs-validation" method="post" novalidate action="{{ url('/jabatan/simpan') }}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-12 mb-2">
                <label for="rolename">Nama Jabatan</label>
                <input type="text" name="rolename" value="{{ old('rolename', $data->rolename) }}" class="form-control" id="rolename" placeholder="Nama Jabatan" required>
              </div>
                <!-- <div class="col-md-12">
                  <label for="roledetail">Admin Aplikasi (Tidak/Ya)</label>
                  <div class="">
                    <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                      <input type="checkbox" name="roleisadmin">
                      <span class="slider round"></span>
                    </label>
                  </div>
                </div> -->
              <div class="col-md-12 mb-2">
                <label for="roledetail">Detail Jabatan</label>
                <textarea class="form-control" name="roledetail" >{{ old('roledetail', $data->roledetail) }}</textarea>
              </div>
              <div class="col-md-12 mb-2">
                <label for="user">User</label>
                <select class="form-control select2" name="userid[]" multiple="multiple">
                  @foreach( $user as $key=>$u)
                  <?php
                    $userActive = in_array($u->id, $data->userid);
                    $selectedUser = $userActive ? ' selected' : null;
                  ?>
                    <option value="{{$u->id}}" {!! $selectedUser !!}>{{$u->username}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-12 mb-5"
                <legend>Hak Akses</legend>
                <div class="row row-sm mg-b-10">
                @foreach (Perm::all() as $key=>$group)
                <div class="col-sm-2">
                  <label><b>{{ $group->module}}</b></label>
                  @foreach($group->actions as $act)
                    <?php
                      $permissionActive = in_array($act->raw, $data->rolepermissions ? : []);
                      $checkedStr = $permissionActive ? 'checked="checked"' : null;
                    ?>
                    <div class="custom-control custom-switch">
                      <input type="checkbox" name="rolepermissions[]" class="custom-control-input" value="{{$act->raw}}" id="{{$act->raw}}" {!! $checkedStr !!} >
                      <label class="custom-control-label" for="{{$act->raw}}">{{$act->value}}</label>
                    </div>
                  @endforeach
                  </div>
                @endforeach    
                </div>
              </div>
            </div>
            <div class="float-right">
              <a href="{{ url('/jabatan') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
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
    $('.select2').select2({
      tags: true,
      placeholder: 'Pilih',
      searchInputPlaceholder: 'Search options'
    });
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