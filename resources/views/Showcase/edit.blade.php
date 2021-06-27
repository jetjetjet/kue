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
.icons {
  position: absolute;
  right: 0;
  top: 0;
  bottom: 0;
  padding: 9px;
}
</style>
<div class="title">
    <h3>{{ trans('fields.showcase') }}</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ trans('fields.masterData') }}</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ trans('fields.showcase') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} {{ trans('fields.showcase') }}</a></li>
  </ol>
@endsection

@section('content-form')

  @if(isset($data->id))
  <div class="alert alert-light-warning" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg> ... </svg></button>
    <strong>Primary!</strong> Lorem Ipsum is simply dummy text of the printing.
  </div>
  @endif
  <div class="d-flex justify-content-between">
    <div class="col-3">
      <div class="skills layout-spacing">
        <div class="widget-content widget-content-area">
          <div class="text-center">
            <span class="profile-picture">
              <img class="editable img-fluid" alt=" Avatar" id="avatar" src="{{ '/kincaycake' . $data->productimg }}">
            </span>
          </div>
          <div class="col-12 my-3">
            <input type="text" id="prodName" value="{{ $data->productname }}" style="font-weight: bold;" class="form-control text-right" readonly>
          </div>
        </div>
      </div>
    </div>
    <div clas="col-9 layout-top-spacing">
      <div class="skills layout-spacing">
        <div class="widget-content widget-content-area">
          <div class="row">
            <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
              <div class="statbox">
              <div class="widget-header">                                
                <div class="row">
                  <div class="col-12 mb-2 ">
                    <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} {{ trans('fields.showcase') }}</h4>
                  </div>                                                                        
                </div>
              </div>
              <div class="widget-content">
                <form class="needs-validation" method="post" novalidate action="{{ url('/showcase/simpan') }}" enctype="multipart/form-data">
                  <div class="form-row">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                    <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
                    @if(isset($data->id))
                    <div class="col-12 mb-2">
                      <label for="showcasecode">{{ trans('fields.code') }} {{ trans('fields.production') }} </label>
                      <input type="text" id="showcasecode" name="showcasecode" value="{{ $data->showcasecode }}" style="font-weight: bold;" class="form-control" readonly>
                    </div>
                    @endif
                    @if(empty($data->showcaseexpiredat))
                      <div class="col-12">
                        <label for="cate">{{ trans('fields.name') }} {{ trans('fields.product') }}</label>
                        <select class="" id="productsearch" name="showcaseproductid">
                          <option value="">Hapus</option>
                          @if($data->showcaseproductid)
                            <option value="{{$data->showcaseproductid}}" selected="selected">{{$data->productcode}} - {{$data->productname}}</option>
                          @endif
                        </select>
                      </div>
                      <div class="col-6 mb-2">
                        <label for="name">{{ trans('fields.price') }}</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                          </div>
                          <input type="text" id="prodPrice" value="{{ $data->productprice }}" style="font-weight: bold;" class="form-control text-right" readonly>
                        </div>
                      </div>
                      <div class="col-6 mb-2">
                        <label for="name">{{ trans('fields.qty') }} {{ trans('fields.showcase') }}</label>
                        <input type="number" id="showcaseqty" name="showcaseqty" value="{{ old('showcaseqty', $data->showcaseqty) }}" class="form-control text-right">
                      </div>
                      <div class="col-6 mb-2">
                        <label for="showcasedate">{{ trans('fields.date') }} {{ trans('fields.product') }} </label>
                        <input type="text" name="showcasedate" id="showcasedate" value="{{ old('showcasedate', $data->showcasedate) }}" class="form-control flatpickr flatpickr-input active">
                      </div>
                      <div class="col-6 mb-2">
                        <label for="showcaseexpdate">{{ trans('fields.expDate') }} {{ trans('fields.product') }}</label>
                        <input type="text" name="showcaseexpdate" id="showcaseexpdate" value="{{ old('showcaseexpdate', $data->showcaseexpdate) }}" class="form-control flatpickr flatpickr-input active" {{ $data->showcaseexpdate ? "" : "disabled" }} >
                      </div>
                    @else
                      <div class="col-12">
                        <label for="cate">{{ trans('fields.name') }} {{ trans('fields.product') }}</label>
                        <input value="{{$data->productcode}} - {{$data->productname}}" class="form-control" readonly>
                      </div>
                      <div class="col-6 mb-2">
                        <label for="name">{{ trans('fields.price') }}</label>
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                          </div>
                          <input type="text" id="prodPrice" value="{{ $data->productprice }}" style="font-weight: bold;" class="form-control text-right" readonly>
                        </div>
                      </div>
                      <div class="col-6 mb-2">
                        <label for="name">{{ trans('fields.qty') }} {{ trans('fields.showcase') }}</label>
                        <input type="number" id="showcaseqty" name="showcaseqty" value="{{ old('showcaseqty', $data->showcaseqty) }}" class="form-control text-right" readonly>
                      </div>
                      <div class="col-6 mb-2">
                        <label for="showcasedate">{{ trans('fields.date') }} {{ trans('fields.product') }} </label>
                        <input type="text" name="showcasedate" value="{{ old('showcasedate', $data->showcasedate) }}" class="form-control" readonly>
                      </div>
                      <div class="col-6 mb-2">
                        <label for="showcaseexpdate">{{ trans('fields.expDate') }} {{ trans('fields.product') }}</label>
                        <input type="text" name="showcaseexpdate" value="{{ old('showcaseexpdate', $data->showcaseexpdate) }}" class="form-control" readonly {{ $data->showcaseexpdate ? "" : "disabled" }} >
                      </div>
                    @endif
                  </div>
                  <div class="float-left mt-2">
                    @if(isset($data->id) && empty($data->showcaseexpiredat) && Perm::can(['order_pelayan']))
                      <a type="button" id="expBtn" class="btn btn-danger mt-2">{{ trans('fields.expired') }}</a>
                    @endif
                  </div>
                  <div class="float-right mt-2">
                    <a href="{{ url('/showcase') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
                    @if(empty($data->showcaseexpiredat))
                    <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
                    @endif
                  </div>
                </form>
              </div>
              @if(isset($data->id))
              <hr/>
              <div class="accordion" id="accordionExample">
                <div class="card">
                  <div class="card-header" id="headingThree">
                    <section class="mb-0 mt-0">
                      <div role="menu" class="collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                      {{ trans('fields.log') }}  
                        <div class="icons"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></div>
                      </div>
                    </section>
                  </div>
                  <div id="collapseThree" class="collapse show" aria-labelledby="headingThree" data-parent="#accordionExample">
                    <div class="card-body">
                      <div class="d-flex justify-content-between">
                        <div class="col">
                          <strong>{{ trans('fields.createdBy') }}</strong>
                          <p><strong>{{ $data->showcasecreatedby }}</strong> - {{ $data->showcasecreatedat }}</p>
                        </div>
                        @if(isset($data->showcasemodifiedat))
                        <div class="col">
                          <strong>{{ trans('fields.modifiedBy') }}</strong>
                          <p><strong>{{ $data->showcasemodifiedby }}</strong> - {{ $data->showcasemodifiedat }}</p>
                        </div>
                        @endif
                        @if(isset($data->showcasesoldat))
                        <div class="col">
                          <strong>{{ trans('fields.outOfStock') }}</strong>
                          <p><strong>{{ $data->showcasesoldby }}</strong> - {{ $data->showcasesoldat }}</p>
                        </div>
                        @endif
                        @if(isset($data->showcaseexpiredat))
                        <div class="col">
                          <strong>{{ trans('fields.expired') }}</strong>
                          <p><strong>{{ $data->showcaseexpiredat }}</strong> - {{ $data->showcaseexpiredby }}</p>
                        </div>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
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
    $('#expBtn').click(function(){
      let qty=$('#showcaseqty').val()
      $.ajax({
        url: "{{url('showcase/expired')}}"+"/"+"{{$data->id}}",
        type: "post",
        data: {
          expiredqty: qty
        },
        success: function(result){
          let msg=result.messages[0]
          if(result.status == "success"){
            toast({
              type: 'success',
              title: msg
            })
            location.reload()
          } else{
            toast({
              type: 'error',
              title: msg
            })
          }
        },
        error: function(error){
          console.log(error)
        }
      })
    })

    inputSearch('#productsearch', "{{ Url('/product/search-showcase') }}", 'resolve', function(item) {
      return {
        text: item.text,
        id: item.id,
        img: item.productimg,
        productname: item.productname,
        price: item.productprice
      }
    });

    $('#productsearch').on('select2:select', function (e) {
      let data = e.params.data;
      $('#avatar').attr('src', '/kincaycake' + data.img)
      $('#prodName').val(data.productname)
      $('#prodPrice').val(formatter.format(data.price))
      $('#productsearch').attr('data-has-changed', '1');
    });

    $('#productsearch').on('select2:clear', function (e) {
      let data = e.params.data;
      $('#avatar').attr('src', '')
      $('#prodName').val(null)
      $('#prodPrice').val(0)
      $('#productsearch').attr('data-has-changed', '1');
    });

    let f1 = flatpickr($('#showcasedate'), {
      altinput: true,
      altformat: "Y-m-d",
      dateFormat: "d-m-Y",
      defaultDate: "{{ old('showcasedate',$data->showcasedate) ?? 'today'}}",
      onChange: function (selectedDates, dateStr, instance) {
        expPicker.set("minDate", dateStr);
        $('#showcaseexpdate').removeAttr('disabled')
      }
    });

    let expPicker = flatpickr($('#showcaseexpdate'), {
      altinput: true,
      altformat: "Y-m-d",
      dateFormat: "d-m-Y",
      defaultDate: "{{ old('showcaseexpdate',$data->showcaseexpdate) ?? 'today'}}",
    });
    
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

    //Delete deleteOrder
    $('#oosBtn').on('click', function (e) {
      e.preventDefault();
      
      const url = "{{ url('showcase/out-of-stock') . '/' }}" + '{{$data->id}}';
      const title = 'Hapus Pesanan';
      gridDeleteInput3(url, title, null, function(callb){
        setTimeout(() => {
          window.location = "{{ url('/order/meja/view') }}";
        }, 2000);
      });
    });
  })
</script>
@endsection