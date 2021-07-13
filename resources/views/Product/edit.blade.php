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

.remove-image {
display: none;
position: absolute;
border-radius: 10em;
padding: 2px 6px 3px;
text-decoration: none;
font: 700 21px/20px sans-serif;
background: #555;
border: 3px solid #fff;
color: #FFF;
box-shadow: 0 2px 6px rgba(0,0,0,0.5), inset 0 2px 4px rgba(0,0,0,0.3);
  text-shadow: 0 1px 2px rgba(0,0,0,0.5);
  -webkit-transition: background 0.5s;
  transition: background 0.5s;
}
.remove-image:hover {
 background: #E54E4E;
  padding: 3px 7px 5px;
}
.remove-image:active {
 background: #E54E4E;
}
</style>
<div class="title">
    <h3>{{ trans('fields.product') }}</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ trans('fields.masterData') }}</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ trans('fields.product') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} {{ trans('fields.product') }}</a></li>
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
          <form class="needs-validation" method="post" novalidate action="{{ url('/product/simpan') }}" enctype="multipart/form-data">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-12 mb-2">
                <label for="name">{{ trans('fields.name') }} {{ trans('fields.product') }}</label>
                <input type="text" name="productname" value="{{ old('productname', $data->productname) }}" class="form-control" id="name" placeholder="Nama" required>
              </div>
              <div class="col-md-6 mb-2">
                <label for="price">Harga</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                  </div>
                  <input name="productprice" type="number" min="0" value="{{ old('productprice', $data->productprice) }}" class="form-control text-right nominus" id="pricing" placeholder="Harga" required>
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="cate">{{ trans('fields.category') }} {{ trans('fields.product') }}</label>
                <div class="input-group">
                  <select class="" id="productpcsearch" name="productpcid">
                    <option value="">Hapus</option>
                    @if($data->productpcid)
                      <option value="{{$data->productpcid}}" selected="selected">{{$data->productpcname}}</option>
                    @endif
                  </select>
                  <div class="input-group-append" style="margin-bottom:auto">
                    <button class="btn btn-info btn-flat mt-1" id="newCate" type="button">Tambah Baru</button>
                  </div>
                </div>
              </div>
              <div class="col-md-12 mb-2">
                <label for="detail">Detail Menu</label>
                <textarea name="productdetail" rows="3" class="form-control" id="detail" placeholder="Detail Menu" >{{ old('productdetail', $data->productdetail) }}</textarea>
              </div>
              <div class="col-md-6 mb-2">
                <div class="custom-file-container" data-upload-id="myFirstImage">
                  <label>Gambar Menu <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                  <label class="custom-file-container__custom-file" >
                    <input name="file" type="file" class="custom-file-input" id="productimg" accept="image/*">
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                  </label>
                  <div class="custom-file-container__image-preview">
                  </div>
                </div>
              </div>
              @if(isset($data->productimg))
              <div class="col-md-6 imgView">
                <div class="text-center">
                <input type="hidden" id="productimg" name="productimg" value="{{ $data->productimg }}" /> 
                  <img src="{{ asset('storage/images/products/' . $data->productimg) }}" style="height:420px; width:420px" class="p-3 rounded" alt="Produk">
                  <button id="delCurImg" class="remove-image" title="Hapus Gambar" style="display: inline;">&#215;</button>
                </div>
              </div>
              @endif
            </div>
            <div class="float-right">
              <a href="{{ url('/product') }}" type="button" class="btn btn-danger mt-2" type="submit">{{ isset($data->id) ? trans('fields.back') : trans('fields.cancel') }}</a>
              <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @if(isset($data->id))
  <hr/>
  <div class="accordion" id="accordionExample">
    <div class="card">
      <div class="card-header" id="headingThree">
        <section class="mb-0 mt-0">
          <div role="menu" class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
          {{ trans('fields.log') }}  
            <div class="icons float-right"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
          </div>
        </section>
      </div>
      <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionExample">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="col">
              <strong>{{ trans('fields.createdBy') }}</strong>
              <p><strong>{{ $data->productcreatedby }}</strong> - {{ $data->productcreatedat }}</p>
            </div>
            @if(isset($data->productmodifiedat))
            <div class="col">
              <strong>{{ trans('fields.modifiedBy') }}</strong>
              <p><strong>{{ $data->productmodifiedby }}</strong> - {{ $data->productmodifiedat }}</p>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif
  
  <div id="popCate" class="d-none">
    <div class="form-horizontal">
      <div class="form-group required">
        <label for="nama">Nama Kategori</label>
        <input type="text" id="pcname" name="pcname" class="form-control" required>
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
    
    inputSearch('#productpcsearch', "{{ Url('/product-category/search') }}", 'resolve', function(item) {
      return {
        text: item.text,
        id: item.id
      }
    });

    $('#productpcsearch').on('select2:select', function (e) {
      $('#productpcsearch').attr('data-has-changed', '1');
    });

    $('#newCate').on('click',function(){
      var modal = showPopupForm(
        $(this),
        { btnType: 'primary', keepOpen: true },
        'Tambah Kategori Product',
        $('#popCate'),
        '{{ url("product-category/save") }}',
        function ($form){
          return {
            pcname: $form.find('[name=pcname]').val()
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

    $('#delCurImg').on('click', function(){
      $('.imgView').remove();
    })

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