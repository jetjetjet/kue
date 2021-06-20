@extends('Layout.index-notopbar')

@section('content-breadcumb')
  <link rel="stylesheet" href="{{ url('/') }}/plugins/font-icons/fontawesome/css/regular.css">
  <link rel="stylesheet" href="{{ url('/') }}/plugins/font-icons/fontawesome/css/fontawesome.css">
  <style>
    .overlay { 
      background: rgba(77, 77, 77, .9);
      color: #393839;
      opacity: 1;
    }
    .dtl-order td, .dtl-order th {
      padding: 0;
    }

    .text-div {
      border-bottom: 2px dotted #999
    }
  </style>
  <style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.input-number {
  width: 30px;
  padding: 0 0 0 0px;
  vertical-align: top;
  text-align: center;
  outline: none;
}

.input-number,
.input-number-decrement,
.input-number-increment {
  border: 1px solid #ccc;
  height: 20px;
  user-select: none;
}

.input-number-decrement,
.input-number-increment {
  display: inline-block;
  width: 20px;
  line-height: 18px;
  background: #f1f1f1;
  color: #444;
  text-align: center;
  font-weight: bold;
  cursor: pointer;
  margin: 0;
}
.input-number-decrement:active,
.input-number-increment:active {
  background: #ddd;
}

.input-number-decrement {
  border-right: none;
  border-radius: 1px 0 0 1px;
}

.input-number-increment {
  border-left: none;
  border-radius: 0 1px 1px 0;
}

.input-number-sm {
  width: 80px;
  padding: 0 12px;
  vertical-align: top;
  text-align: center;
  outline: none;
}

.input-number-sm,
.input-number-decrement-sm,
.input-number-increment-sm {
  border: 1px solid #ccc;
  height: 40px;
  user-select: none;
}

.input-number-decrement-sm,
.input-number-increment-sm {
  display: inline-block;
  width: 30px;
  line-height: 38px;
  background: #f1f1f1;
  color: #444;
  text-align: center;
  font-weight: bold;
  cursor: pointer;
}
.input-number-decrement-sm:active,
.input-number-increment-sm:active {
  background: #ddd;
}

.input-number-decrement-sm {
  border-right: none;
  border-radius: 4px 0 0 4px;
}

.input-number-increment-sm {
  border-left: none;
  border-radius: 0 4px 4px 0;
}
</style>
  <div class="title">
    <h3>Pesanan</h3>
  </div>
@endsection

@section('content-body')
<div class="widget-content widget-content-area br-4">
  <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="statbox box box-shadow">
      <div class="row">
        <div class="col-md-7 col-sm-12">
          <div class="widget-content pill-justify-right">
            <ul class="nav nav-pills mb-3 mt-3 justify-content-end" id="justify-right-pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="justify-right-pills-home-tab" data-toggle="pill" href="#justify-right-pills-home" role="tab" aria-controls="justify-right-pills-home" aria-selected="true">Makanan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="justify-right-pills-profile-tab" data-toggle="pill" href="#justify-right-pills-profile" role="tab" aria-controls="justify-right-pills-profile" aria-selected="false">Minuman</a>
              </li>
            </ul>
            <div class="tab-content" id="justify-right-pills-tabContent">
              <div class="tab-pane fade show active" id="justify-right-pills-home" style="padding:0 0 0 0" role="tabpanel" aria-labelledby="justify-right-pills-home-tab">
                @foreach($menu['Makanan'] as $cat)
                  <div class="text-div"><h4><span>{{$cat['nama']}}</span></h4></div>
                  <section class="row">
                  @foreach($cat['pilihan'] as $mkn)
                  <div>
                    <a class="{{$mkn['menuavaible'] != true ? 'card' : 'menuCard'}}" data-id="{{$mkn['id']}}" data-menutext="{{$mkn['menuname']}}" data-price="{{$mkn['menuprice']}}">
                      <div class="category-tile">
                        <img width="120" height="120" src="{{ isset($mkn->menuimg) ? asset($mkn->menuimg) : asset('/public/images/fnb.jpg') }}" onerror="this.onerror=null;this.src='{{asset('/images/fnb.jpg')}}';" >
                        <span>{{$mkn['menuname']}} {{$mkn['menuavaible'] != true ? " - Stok Kosong" : ""}}</span>
                      </div>
                    </a>
                  </div>
                  @endforeach
                </section>
                @endforeach
              </div>
              <div class="tab-pane fade" id="justify-right-pills-profile" role="tabpanel" aria-labelledby="justify-right-pills-profile-tab">
                @foreach($menu['Minuman'] as $min)
                  <div class="text-div"><h4><span>{{$min['nama']}}</span></h4></div>
                  <section class="row">
                  @foreach($min['pilihan'] as $item)
                  <div>
                    <a class="{{$item['menuavaible'] != true ? 'card' : 'menuCard'}}" data-id="{{$item['id']}}" data-menutext="{{$item['menuname']}}" data-price="{{$item['menuprice']}}">
                      <div class="category-tile">
                        <img width="120" height="120" src="{{ isset($item->menuimg) ? asset($item->menuimg) : asset('/public/images/fnb.jpg') }}" onerror="this.onerror=null;this.src='{{asset('/images/fnb.jpg')}}';">
                        <span>{{$item['menuname']}} {{$item['menuavaible'] != true ? " - Stok Kosong" : ""}}</span>
                      </div>
                    </a>
                  </div>
                  @endforeach
                </section>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-5 col-sm-12">
          <div class="widget-content widget-content-area" style="margin-bottom:25px">
            <form id="orderMenuForm" method="post" novalidate action="{{url('/order/save')}}">
              <div class="orderCust" style="padding-bottom:5px">
                <div class="form-group row">
                  <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Jenis Pesanan</label>
                  <div class="col-sm-8">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                    <input type="hidden" id="orderType" value="DINEIN" name="ordertype" readonly>
                    <input type="text" id="boardText1" name="ordertypetext" value="Makan Di Tempat" class="form-control form-control-sm" readonly>
                  </div>
                </div>
                <div class="form-group row divMeja">
                  <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">No. Meja</label>
                  <div class="col-sm-8">
                    <input type="hidden" id="boardId" name="orderboardid" id="colFormLabelSm">
                    <input type="text" id="boardText" name="orderboardtext" class="form-control form-control-sm" readonly>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <table id="detailOrder" class="table table-hover dtl-order">
                  <thead>
                    <tr>
                      <th width="40%">Menu</th>
                      <th>Harga</th>
                      <th width="20%" class="text-center">qty</th>
                      <th>Total</th>
                      <th>Cttn</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div class="float-left">
                <h2>Total =</h2>
              </div>
              <div class="float-right">
                <h2 id="idTotal">0</h2>
                <input type="hidden" name="orderprice" required>
              </div>
              <div class="float-right">
                <button type="button" id="addToTableMenu" class="btn btn-sm btn-success d-none add-row" >
                  <span class="fa fa-plus fa-fw"></span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="row fixed-bottom">
        <div class="col-sm-12 ">
          <div class="widget-content widget-content-area" style="padding:10px">
            <div class="float-left">
              <a href="{{url('/order/meja/view')}}" type="button" id='back' class="btn btn-warning mt-2">Kembali</a>
            </div>
            <div class="float-right">
              <a href="" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah Meja</a>
              <a type="button" id="saveAndPrint" data-print="1" class="btn btn-primary mt-2 prosesOrder">Simpan & Cetak</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mejaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Ubah Meja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="col-md-12">
            <label for="orderboardid">Nomor Meja</label>
            <select class="form-control form-control-sm cariMeja" id="cariMeja" name="orderboardid">
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="custButton" style="min-width: 75px;" class="btn btn-success btn-sm font-bold modal-add-row">Ubah</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <table class="table mb-4">
            <tbody>
              <tr>
                <td class="text-left">Pilihan Menu</td>
                <td class="text-primary" id="menuPopupText"></td>
              </tr>
              <tr>
                <td class="text-left">Harga</td>
                <td class="text-primary" id="menuPopupPrice"></td>
              </tr>
              <tr>
                <td class="text-left">Jumlah</td>
                <td class="text-primary" >
                  <span id="keymin" class="input-number-decrement-sm">–</span>
                    <input type="number" id="menuPopupQty" name="menuPopupQty" class="input-number-sm text-right" value="1" min="0">
                  <span id="keyplus" class="input-number-increment-sm">+</span> 
                  <!-- <input type="number" class="form-control form-control-sm text-right" id="menuPopupQty" name="menuPopupQty" class="menuPopupQty text-right"/> -->
                </td>
              </tr>
              <tr>
                <td class="text-left">Catatan</td>
                <td class="text-primary" >
                  <input type="text" id="menuRemark" name="menuRemark" class="form-control"/>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="popDismiss" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="popSubmit" style="min-width: 75px;" class="btn btn-info btn-sm font-bold modal-add-row">Tambah</button>
      </div>
    </div>
  </div>
</div>

<table id="tabel" class="row-template d-none">
    @include('Order.subOrderCust')
</table>
@endsection

@section('js-body')
<script>
  let totalPrice = 0;
  $(document).ready(function (){
    //hotkeys
    $(this).on('shown.bs.modal', function() {
      Mousetrap.bind('-', function() {
        $('#uiModalInstance').find('#keymin').trigger('click')
      })
      Mousetrap.bind('+', function() {
        $('#uiModalInstance').find('#keyplus').trigger('click')
      })
      Mousetrap.bind('enter', function() {
        $('#uiModalInstance').find('#popSubmit').trigger('click')
      })
      Mousetrap.bind('backspace', function() {
        $('#uiModalInstance').find('#popDismiss').trigger('click')
      })
    })
    $(this).on('hidden.bs.modal', function() {
      Mousetrap.bind('enter', function() {
        $('#prosesOrder').trigger('click')
      })
    })
    //endhotkeys
    
    initMeja($('#orderType').val());

    let $targetContainer = $('#detailOrder');
    setupTableGrid($targetContainer);

    $('.prosesOrder').on('click', function(){
      if(totalPrice <= 0){
        toast({
            type: 'error',
            title: 'total jumlah pesanan tidak boleh 0 / Pesanan tidak ada!',
            padding: '2em',
          });
      } else {
        let print = $(this).attr('data-print');
        $('.prosesOrder').attr('dissabled');
        if(print){
          let form = $('#orderMenuForm');
          let url = "{{ url('order/api-save') }}";
    
          $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(),
            success: function(data){
              window.location.href = data;
              setTimeout(() => {
                window.location.href = "{{ url('/order/meja/view') }}"
              }, 1000);
            },
            error: function(data){
              toast({
                type: 'error',
                title: 'Tidak dapat mencetak struk!',
                padding: '2em',
              });
              setTimeout(() => {
                window.location.href = "{{ url('/order/meja/view') }}"
              }, 1000);
            }
         });
        } else{
          $('#orderMenuForm').submit();
        }
      }
    })
    
    $('#orderType').on('change',function(){
      let val = $(this).val();
      initMeja(val);
    });

    //Ubah Meja
    $('#headerOrder').on('click', function(){
      let idMeja, textMeja;
      $(this).attr('data-toggle', 'modal');
      $(this).attr('data-target', '#uiModalInstance');

      $.fn.modal.Constructor.prototype._enforceFocus = function() {};
      let $modal = cloneModal($('#mejaModal'));

      $modal.on('show.bs.modal', function (){
        let idBoard = $('[name="orderboardid"]').val();

        if(idBoard){
          changeOptSelect2($('.cariMeja'), "{{ Url('/meja/cariTersedia') }}" + "/" + idBoard)
        }

        inputSearch('.cariMeja', "{{ Url('/meja/cariTersedia') }}", 'resolve', function(item) {
          return {
            text: item.text,
            id: item.id
          }
        });
        
      }).modal('show');

      $modal.find('#custButton').on('click',function(){

        if (idMeja != null){
          $('[name="orderboardid"]').val(idMeja);
          $('[name="orderboardtext"]').val(textMeja);
        }
        
        $modal.modal('hide');
      })

      $('.cariMeja').on('select2:select', function (e) {
        textMeja = e.params.data.text;
        idMeja = e.params.data.id;
      });
    })

    $('.menuCard').on('click', function(){
      let menuPrice = $(this).attr('data-price'),
          menuText = $(this).attr('data-menutext'),
          menuId = $(this).attr('data-id');

      let bodyPopup = {
        'text' : menuText,
        'price' : menuPrice
      };
      
      showPopupOrder(bodyPopup, function(){
        $("#addToTableMenu").attr("data-pMenuText",menuText);
        $("#addToTableMenu").attr("data-pMenuPrice",menuPrice);
        $("#addToTableMenu").attr("data-pId",menuId);

        $('#addToTableMenu').trigger('click');
      });
    });

    caclculatedOrder()
  });

  function setupTableGrid($targetContainer)
  {
    // Setups add grid. 
    $targetContainer.registerAddRow($('.row-template'), $('.add-row'));
    $targetContainer.on('row-added', function (e, $row){
    let rowMenuText = $("#addToTableMenu").attr('data-pMenuText'),
        rowMenuPrice = $("#addToTableMenu").attr('data-pMenuPrice'),
        rowId = $("#addToTableMenu").attr('data-pId'),
        qty = $('#uiModalInstance').find('#menuPopupQty').val(),
        remark = $('#uiModalInstance').find('#menuRemark').val(),
        tprice = qty*rowMenuPrice;
      $row.find('[id^=dtl][id$="[odmenutext]"]').html(rowMenuText);
      $row.find('[id^=dtl][id$="[odprice]"]').html(formatter.format(rowMenuPrice));
      $row.find('[id^=dtl][id$="[odtotalprice]"]').html(formatter.format(tprice));
      $row.find('[name^=dtl][name$="[odtotalprice]"]').val(tprice);
      $row.find('[id^=dtl][id$="[odremark]"]').html(remark);
      $row.find('[name^=dtl][name$="[odmenuid]"]').val(rowId);
      $row.find('[name^=dtl][name$="[odmenutext]"]').val(rowMenuText);
      $row.find('[name^=dtl][name$="[odqty]"]').val(qty);
      $row.find('[name^=dtl][name$="[odprice]"]').val(rowMenuPrice);
      $row.find('[name^=dtl][name$="[odremark]"]').val(remark);
      window.setTimeout(() => {
        caclculatedOrder()        
      }, 0);
    })
    .on('row-removing', function (e, $row){
      window.setTimeout(() => {
        caclculatedOrder()        
      }, 0);
    })
    .on('row-delivering', function (e, $row){
      
    })
    .on('row-counterup', function(e, $row){
      let rQty = $row.find('[name^=dtl][name$="[odqty]"]');
      let  min = rQty.attr('min') || false;
      
      let oldVal = Number(rQty.val());
      let newVal = oldVal + 1;
      
      rQty.val(newVal);
      rQty.trigger("change");
    })
    .on('row-counterdown', function(e, $row){
      let rQty = $row.find('[name^=dtl][name$="[odqty]"]');
      let min = rQty.attr('min') || false;
      let oldVal = Number(rQty.val());

      let newVal = oldVal;
      if (!min || oldVal <= min) {
        let newVal = oldVal;
      } else {
        newVal = oldVal - 1;
      }

      rQty.val(newVal);
      rQty.trigger("change");
    })
    .on('row-updating', function (e, $row){
      let newQty = $row.find('[name^=dtl][name$="[odqty]"]').val(),
        price = $row.find('[name^=dtl][name$="[odprice]"]').val();
      const newTotalPrice = Number(newQty) * Number(price);
      $row.find('[id^=dtl][id$="[odtotalprice]"]').html(formatter.format(newTotalPrice));
      $row.find('[name^=dtl][name$="[odtotalprice]"]').val(newTotalPrice);

      caclculatedOrder()    
    });
  }

  function caclculatedOrder(){
    let gridRow = $('#detailOrder').find('[id^=dtl][id$="[odmenutext]"]').closest('tr');
    totalPrice = 0;
    gridRow.each(function(){
      let price = $(this).find('[name^=dtl][name$="[odtotalprice]"]').val();
      totalPrice += Number(price);
    });
    $('#idTotal').html(formatter.format(totalPrice));
    $('[name="orderprice"]').val(totalPrice);
  }

  function initMeja(val){
    if(val == "TAKEAWAY"){
      $('.divMeja').addClass('d-none');
      $('#headerOrder').addClass('d-none');
      $('#saveAndPrint').addClass('d-none');
    } else {
      $('.divMeja').removeClass('d-none')
      $('#headerOrder').removeClass('d-none');
      $('#saveAndPrint').removeClass('d-none');
    }
  }

  const toast = swal.mixin({
    toast: true,
    position: 'center',
    showConfirmButton: false,
    timer: 3000,
    padding: '2em'
  });
</script>
@endsection