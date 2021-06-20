@extends('Layout.layout-form')

@section('breadcumb')
  <link href="{{ url('/') }}/assets/css/tables/table-basic.css" rel="stylesheet" type="text/css" />
  <div class="title">
    <h3>Promo</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/promo') }}">Promo</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Promo</a></li>
  </ol>
@endsection

@section('content-form')
<?php
  $canEdit = isset($data->editable) ? ($data->editable && Perm::can(['promo_simpan'])) : false;
  $subs = isset($data->id) ? $data->sub : old('sub', []);
  // dd($subs);
?>
  <!-- <div class="widget-content widget-content-area br-6">
    <div class="row"> -->
      <div class="alert alert-light-danger mb-4 d-none subAlert" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        <b>Harga menu promo tidak boleh minus!</b>
      </div>
      <div class="alert alert-light-danger mb-4 d-none pembulatanPromo" role="alert">
        <button type="button" class="close" data-dismiss="alert" min="500" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        <b>Potongan promo harus kelipatan dari 500!</b>
      </div>
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox widget box box-shadow">
        <div class="widget-header">                                
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Promo</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{ url('/promo/simpan') }}">
            <div class="form-row">
              <div class="col-md-6 mb-2">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
                <label for="userfullname">Nama Promo</label>
                <input type="text" name="promoname" value="{{ old('promoname', $data->promoname) }}" class="form-control" id="promoname" placeholder="Nama Promo" {{ !$canEdit ? 'readonly' : '' }} required>
                <div class="invalid-feedback">
                  Nama promo harus diisi!
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="promodiscount">Potongan Promo</label>
                <input type="number" name="promodiscount" id="promodiscount" value="{{ old('promodiscount', $data->promodiscount) }}" class="form-control" {{ !$canEdit ? 'readonly' : '' }} required>
                <div class="invalid-feedback">
                  Potongan promo harus diisi dan kelipatan dari 500!
                </div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-6 mb-2">
                <label for="userjoindate">Awal Promo</label>
                <input type="text" name="promostart" value="{{ old('promostart', $data->promostart) }}" class="form-control  flatpickr flatpickr-input active" id="promostart" {{ !$canEdit ? 'disabled' : '' }} required>
                <div class="invalid-feedback">
                  Tanggal Awal Promo harus diisi!
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <label for="userjoindate">Akhir Promo</label>
                <input type="text" name="promoend" value="{{ old('promoend', $data->promoend) }}" class="form-control  flatpickr flatpickr-input active" id="promoend" disabled required>
                <div class="invalid-feedback">
                  Tanggal Akhir Promo harus diisi!
                </div>
              </div>
              <div class="col-md-12 mb-2">
                  <label for="floo">Detail Promo</label>
                  <textarea rows="2" name="promodetail" {{ !$canEdit ? 'readonly' : '' }} class="form-control" >{{ old('promodetail', $data->promodetail) }}</textarea>
              </div>
            </div>
            <hr />
            <fieldset>
              <legend>Detail Promo Menu</legend>
              @if($canEdit)
                <div class="float-right mb-1">
                  <button type="button" class="btn btn-sm btn-success add-row {{ empty($data->promoid) ? 'hidden' : null }}">
                    <span class="fa fa-plus fa-fw"></span>&nbsp;Tambah Baru
                  </button>
                </div>
              @endif
              <div class="table-responsive mb-4 mt-4">
                <table id="subOrder" class="table" cellspacing="0" width="100%">
                  <thead>
                      <tr>
                        <th>Menu</th>
                        <th>Tipe</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Harga Promo</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($subs as $sub)
                      @include('Promo.sub', Array('rowIndex' => $loop->index))
                    @endforeach
                  </tbody>
                </table>
              </div>
            </fieldset>
              <div class="float-right">
                <a href="{{ url('/promo') }}" type="button" class="btn btn-danger mt-2" type="submit">{{ !$canEdit ? 'Kembali' : 'Batal' }}</a>
                @if($canEdit)
                  <button class="btn btn-primary mt-2" id="saveBtn" type="submit">Simpan</button>
                @endif
              </div>
          </form>
        </div>
      </div>
    <!-- </div>
  </div> -->
  
  <table id="tabel" class="row-template d-none">
    @include('Promo.sub')
  </table>
@endsection

@section('js-form')
<script>
  
  $(document).ready(function (){

    @if(old('promostart',$data->promostart) && $canEdit)
      $('#promoend').removeAttr('disabled')
    @endif

    $('[type=number]').setupMask(0);
    // $('#menuprice').setupMask(0);
    // $('#promoprice').setupMask(0);

    let $targetContainer = $('#subOrder');
    setupTableGrid($targetContainer);

    flatpickr($('#promostart'), {
      enableTime: true,
      altinput: true,
      altformat: "Y-m-d H:i",
      dateFormat: "d-m-Y H:i",
      minDate: "today",
      defaultDate: "{{ old('promostart',$data->promostart) ?? 'today'}}",
      time_24hr: true,
      onChange: function (selectedDates, dateStr, instance) {
        endPicker.set("minDate", dateStr);
        $('#promoend').removeAttr('disabled')
      }
    });
    
    let endPicker = flatpickr($('#promoend'), {
      enableTime: true,
      altinput: true,
      altformat: "Y-m-d H:i",
      dateFormat: "d-m-Y H:i",
      defaultDate: "{{ old('promoend',$data->promoend) ?? 'today'}}",
      minDate: "{{isset($data->promostart) ? $data->promostart : 'today'}}",
      time_24hr: true
    });
    
    $('#promodiscount').on('change', function (e) {
      let promoVal = $(this).val();
      let pembulatan = Math.floor(promoVal % 500)
      if(pembulatan != 0 ){
        $('.pembulatanPromo').removeClass('d-none')
        $("#saveBtn").attr("disabled", "disabled");
        $('html, body').animate({scrollTop:0}, '300')
      } else {
        $('.pembulatanPromo').addClass('d-none')
        let mPrice = $targetContainer.find('.subItem')
        mPrice.each(function(index, item){
          let rowPrice = $(this).find('[name^=sub][name$="[menuPrice]"]').val();
          let calcRow = Number(rowPrice) - promoVal
          $(this).find('[id^=sub][id$="[menuPromo]"]').html(formatter.format(calcRow));
          $(this).find('[name^=sub][name$="[menuPromo]"]').val(calcRow);
        })
        $('#saveBtn').removeAttr('disabled');
      }
    })

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        
        let subRowMinus = true
        let mPrice = $targetContainer.find('[name^=sub][name$="[menupromo]"]')
        mPrice.each(function(index, item){
          let rowPromo = $(this).val();
          if(Number(rowPromo) < 0){
            subRowMinus = false;
            return;
          }
        })
        
        if(!subRowMinus){
          $('.subAlert').removeClass('d-none')
          $('html, body').animate({scrollTop:0}, '300');
          event.preventDefault();
          event.stopPropagation();
        } else {
          $('.subAlert').addClass('d-none')
          let pembulatan = Math.floor($('#promodiscount').val() % 500)
          if (form.checkValidity() === false || pembulatan != 0) {
            if(pembulatan != 0){
              $('.pembulatanPromo').removeClass('d-none')
              $('html, body').animate({scrollTop:0}, '300')
            }
            event.preventDefault();
            event.stopPropagation();
          }else if (form.checkValidity() === true){
            // $("#saveBtn").attr("disabled", "disabled");
          }
          form.classList.add('was-validated');
        }
      }, false);
    });
  })

  function setupTableGrid($targetContainer)
  {
    $targetContainer.registerAddRow($('.row-template'), $('.add-row'));
    $targetContainer.on('row-added', function (e, $row){
      setupDetailPromo($row);
    }).on('row-removing', function (e, $row){
      let idSub = $row.find('[name^=sub][name$="[id]"]').val();
      if(idSub){
        gridDeleteSub("{{ url('promo/hapus-sub') . '/' }}" + idSub,
          'Hapus Menu Promo', 
          'Apakah anda yakin ingin menghapus menu dari promo?', 
          function(data){
            if (data.status == 'success'){
              sweetAlert('Data Dihapus', data.messages[0], 'success')
              $row.remove();
            } else {
              sweetAlert('Kesalahan!', data.messages[0], 'error')
            }
          }
        )
      } else {
        $row.remove();
      }
    })
    
    setupDetailPromo($targetContainer);
  }

  function setupDetailPromo($targetContainer)
  {
    inputSearch($targetContainer.find('[name^=sub][name$="[spmenuid]"]'), 
      "{{ Url('/menu/search') }}", 
      '250px', 
      function(item) {
        return {
          text: item.text,
          id: item.id,
          category: item.menucategory,
          price: item.menuprice,
          type: item.menutype
        }
    });

    $targetContainer.find('[name^=sub][name$="[spmenuid]"]').on('select2:select', function (e) {
      let dt = e.params.data;
      let promoPrice = dt.price - $('#promodiscount').val();

      $targetContainer.find('[name^=sub][name$="[menuname]"]').val(dt.text);
      $targetContainer.find('[id^=sub][id$="[menuType]"]').html(dt.type);
      $targetContainer.find('[name^=sub][name$="[menutype]"]').val(dt.type);
      $targetContainer.find('[id^=sub][id$="[menuCategory]"]').html(dt.category);
      $targetContainer.find('[name^=sub][name$="[menucategory]"]').val(dt.category);
      $targetContainer.find('[name^=sub][name$="[menuprice]"]').val(dt.price);
      $targetContainer.find('[id^=sub][id$="[menuPriceText]"]').html(formatter.format(dt.price));
      $targetContainer.find('[id^=sub][id$="[menuPromo]"]').html(formatter.format(promoPrice));
      $targetContainer.find('[name^=sub][name$="[menupromo]"]').val(promoPrice);

      $targetContainer.find('[id^=sub][id$="[subAvail]"]').removeClass('d-none');
    });
  }
</script>
@endsection