@extends('Layout.layout-form')

@section('breadcumb')
<style>
.input-group > .select2-container--bootstrap {
	width: auto !important;
	flex: 1 1 auto;
}

.input-group > .select2-container--bootstrap .select2-selection--single {
	height: 100%;
	line-height: inherit;
}
</style>
  <div class="title">
    <h3>Menu</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/menu') }}">Menu</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Menu</a></li>
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
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Menu</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content">
          <form class="needs-validation" method="post" novalidate action="{{ url('/menu/simpan') }}" enctype="multipart/form-data">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <input type="hidden" id="idd" name="getid" value="{{ old('getId', $data->getId) }}" />
              <div class="col-md-6 mb-2">
                <label for="name">Nama</label>
                <input type="text" name="menuname" value="{{ old('menuname', $data->menuname) }}" class="form-control" id="name" placeholder="Nama" required>
              </div>
              <div class="col-md-6 mb-2">
                <label for="price">Harga</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                  </div>
                  <input name="menuprice" type="number" min="0" value="{{ old('menuprice', $data->menuprice) }}" class="form-control text-right" id="pricing" placeholder="Harga" required>
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="type">Jenis Menu</label>
                <select class="form-control" id="type" name="menutype">
                  <option value="Makanan" {{ old('menutype', $data->menutype) == 'Makanan' ? ' selected' : '' }}> Makanan</option>
                  <option value="Minuman" {{ old('menutype', $data->menutype) == 'Minuman' ? ' selected' : '' }}> Minuman</option>
                </select>
              </div>
              <div class="col-md-6 mb-2">
                <label for="cate">Kategori Menu</label>
                <div class="input-group">
                  <select class="" id="menumcsearch" name="menumcid">
                    <option value="">Hapus</option>
                    @if($data->menumcid)
                      <option value="{{$data->menumcid}}" selected="selected">{{$data->menumcname}}</option>
                    @endif
                  </select>
                  <div class="input-group-append" style="margin-bottom:auto">
                    <button class="btn btn-info btn-flat mt-1" id="newCate" type="button">Tambah Baru</button>
                  </div>
                </div>
              </div>
              <div class="col-md-12 mb-2">
                <label for="detail">Detail Menu</label>
                <textarea name="menudetail" rows="3" class="form-control" id="detail" placeholder="Detail Menu" >{{ old('menudetail', $data->menudetail) }}</textarea>
              </div>
              <div class="col-md-12 mb-2">
                <label>Status Menu(Kosong, Ada)</label>
                <br>
                @if(isset($data->menuavaible))
                  <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                    <input type="checkbox" id="mav" name="menuavaible" {{ $data->menuavaible ? 'checked' : ''}}>
                    <span class="slider round"></span>
                  </label>
                @elseif(empty($data->menuavaible))
                  <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                    <input type="checkbox" id="mav" name="menuavaible" checked>
                    <span class="slider round"></span>
                  </label>
                  @endif
              </div>
              <div class="col-md-6 mb-2">
                <div class="custom-file-container" data-upload-id="myFirstImage">
                    <label>Gambar Menu <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                    <label class="custom-file-container__custom-file" >
                      <input name="menuimg" type="file" class="custom-file-input" id="menuimg" accept="image/*">
                      <span class="custom-file-container__custom-file__custom-file-control"></span>
                    </label>
                    <div class="custom-file-container__image-preview">
                    </div>
                </div>
              </div>   
              @if(isset($data->menuimg))
              <div class="col-md-6 mb-2">                
                <label for="img"><b>Gambar Menu Saat Ini</b></label>
                <br>
                <div class="n-chk">
                  <label class="new-control new-checkbox new-checkbox-rounded new-checkbox-text checkbox-danger">
                    <input type="checkbox" class="new-control-input" name="delimg" id ="delimg" value = "1">
                    <span class="new-control-indicator"></span><span class="new-chk-content">Hapus Foto</span>
                  </label>
                </div> 
                <br>
                <img src="{{ asset($data->menuimg) }}" style="vertical-align:top"  class="imgrespo"  ></img>
                <input type="hidden" id="hidimg" name="hidimg" value="{{ old('menuimg', $data->menuimg) }}" />                
              </div>
              @endif
            </div>  
              <div class="float-right">
                <a href="{{ url('/menu') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
                <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div id="popCate" class="d-none">
    <div class="form-horizontal">
      <div class="form-group required">
        <label for="nama">Nama Kategori</label>
        <input type="text" id="mcname" name="mcname" class="form-control" required>
      </div>
    </div>
  </div>
@endsection

@section('js-form')
<script>
  const toast = swal.mixin({
    toast: true,
    position: 'center',
    showConfirmButton: false,
    timer: 3000,
    padding: '2em'
  });

  $(document).ready(function (){
    $('[type=number]').setupMask(0);

    inputSearch('#menumcsearch', "{{ Url('/menu-category/search') }}", 'resolve', function(item) {
      return {
        text: item.text,
        id: item.id
      }
    });

    $('#menumcsearch').on('select2:select', function (e) {
      $('#menumcsearch').attr('data-has-changed', '1');
    });

    $('#newCate').on('click',function(){
      var modal = showPopupForm(
        $(this),
        { btnType: 'primary', keepOpen: true },
        'Tambah Kategori Menu',
        $('#popCate'),
        '{{ url("menu-category/save") }}',
        function ($form){
            return {
              mcname: $form.find('[name=mcname]').val()
            };
        },
        //callback
        function (data){
          $('body').removeClass('modal-open');
          toast({
            type: data.status,
            title: data.msg,
            padding: '2em',
          });
        });
    });

    var firstUpload = new FileUploadWithPreview('myFirstImage')
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