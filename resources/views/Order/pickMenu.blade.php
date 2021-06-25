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

    .product_card_featured {
      width: 100%
    }

    .product_card {
      width: 100%;
      margin-right: 7%;
      padding-top: 15px;
      padding-left: 15px;
      padding-right: 15px;
      padding-bottom: 15px;
      box-shadow: 1px 1px 5px 1px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      margin-top: 0px
    }

    .product_card_title {
      position: absolute;
      top: 10px;
      left: 22px;
      font-size: 18px;
      font-weight: 500;
      color: #000000
    }

    .product_card_slider_container {
      width: 100%
    }

    .product_card_item {
      width: 100% !important
    }

    .product_card_image {
      width: 100%
    }

    .product_card_image img {
      width: 100%
    }

    .product_card_content {
      margin-top: 10px
    }

    .product_card_item_category a {
      font-size: 14px;
      font-weight: 400;
      color: rgba(0, 0, 0, 0.5)
    }

    .product_card_item_price_a {
      font-size: 14px;
      font-weight: 400;
      color: rgba(0, 0, 0, 0.6)
    }

    .product_card_item_price_a strike {
      color: red
    }

    .product_card_item_name {
      font-size: 14px;
      color: #000000
    }

    .product_card_item_price {
      font-size: 14px;
      color: #6d6e73
    }

    .available {
      margin-top: 19px
    }

    .available_title {
      font-size: 16px;
      color: rgba(0, 0, 0, 0.5);
      font-weight: 400
    }

    .available_title span {
      font-weight: 700
    }

    @media only screen and (max-width: 991px) {
      .product_card {
        width: 100%;
        margin-right: 0px
      }
    }

    @media only screen and (max-width: 575px) {
      .product_card {
        padding-left: 15px;
        padding-right: 15px
      }

      .product_card_title {
        left: 15px;
        font-size: 16px
      }

      .product_card_slider_nav_container {
        right: 5px
      }

      .product_card_item_name,
      .product_card_item_price {
        font-size: 20px
      }
    }
</style>
  <div class="title">
    <h3>Pesanan</h3>
  </div>
@endsection

@section('content-body')
<?php
  $subs = isset($data->id) ? $data->subs : old('dtl', []);
?>


<div class="d-flex justify-content-between">
  @if(isset($data->ordervoidedat))
    <div class="alert alert-warning" role="alert">
      <strong>Pesanan Dibatalkan!</strong>
        <ul>
          <li>Dibatalkan Oleh: <b>{{$data->ordervoidedusername}}</b></li>
          <li>Dibatalkan Pada: {{$data->ordervoidedat}}</li>
          <li>Alasan: {{$data->ordervoidreason}}</li>
        </ul>
    </div>
  @endif
  <div class="col-6">
    <div class="widget-content widget-content-area">
      <div class="tab-pane fade show active">
        @foreach($products as $cat)
          <div class="text-div"><h4><span>{{$cat['text']}}</span></h4></div>
          <section class="row">
            @foreach($cat['item'] as $shc)
              <div class="col-md-3">
                <a class="productCard px-2 py-1" style="cursor:pointer"
                  data-id="{{$shc->productid}}"
                  data-productname="{{$shc->productname}}"
                  data-productprice="{{$shc->productprice}}"
                  data-productpriceraw="{{$shc->productpriceraw}}"
                  data-productpcid="{{$shc->productpcid}}"
                  data-promoname="{{$shc->promoname}}"
                  data-promoid="{{$shc->promoid}}"
                  data-promodiscount="{{$shc->promodiscount}}"
                > 
                  <div class="product_card">
                    <div class="product_card_slider_container">
                      <div class=" product_card_item">
                        <div class="product_card_image">
                          <img src="{{ isset($shc->productimg) ? asset($shc->productimg) : asset('/public/images/fnb.jpg') }}" onerror="this.onerror=null;this.src='{{asset('/images/fnb.jpg')}}';" alt="">
                        </div>
                        <div class="product_card_content">
                          @if($shc->promodiscount)
                            <div class="product_card_info_line d-flex flex-row justify-content-start">
                              <div class="product_card_item_category">Promo</div>
                              <div class="product_card_item_price_a ml-auto"><strike>{{$shc->productpriceraw}}</strike></div>
                            </div>
                          @endif
                          <div class="product_card_info_line d-flex flex-row justify-content-start">
                            <div class="product_card_item_name">{{$shc->productname}}</div>
                            <div class="product_card_item_price ml-auto">{{$shc->productprice}}</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            @endforeach
          </section>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-6">
    <div class="statbox box" >
      <div class="widget-content widget-content-area" style="margin-bottom:25px">
        <form id="orderProductForm" method="post" novalidate action="{{url('/order/save')}}">
          <div class="orderCust" style="padding-bottom:5px">
            <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
            @if(isset($data->id))
              <div class="form-group row divMeja">
                <div class="col-sm-12">
                  <p><h4>{{ trans('fields.invoiceNumber') }}: <b>{{ old('orderinvoice', $data->orderinvoice) }}</b></h4></p>
                </div>
              </div>
            @endif
          </div>
          <div class="form-row">
            <table id="detailOrder" class="table table-hover dtl-order">
              <thead>
                <tr>
                  <th width="30%">{{ trans('fields.product') }}</th>
                  <th>Tipe Pesananan</th>
                  <th>Harga</th>
                  <th width="20%" class="text-center">qty</th>
                  <th>Total</th>
                  <th>Cttn</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              @foreach ($subs as $sub)
                @include('Order.subOrder', Array('rowIndex' => $loop->index))
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="">
            <div class="float-left">
              <h2>Total =</h2>
            </div>
            <div class="float-right">
              <h2 id="idTotal">0</h2>
              <input type="hidden" name="orderprice" value="{{ old('orderprice', $data->orderprice) }}" required>
              <button type="button" id="addToTableProduct" class="btn btn-sm btn-success d-none add-row" >
                <span class="fa fa-plus fa-fw"></span>
              </button>
            </div>
          </div>
        </form>
      </div>
   </div>
  </div>
</div>
<div class="statbox box box-shadow">
  <div class="row fixed-bottom">
    <div class="col-sm-12 ">
      <div class="widget-content widget-content-area" style="padding:10px">
      @if(!isset($data->ordervoidedat))
          <div class="float-left">
            @if(Perm::can(['order_hapus']) && ($data->orderstatus == 'PROCEED' || $data->orderstatus == 'ADDITIONAL'))
              <a href="" id="deleteOrder" type="button" class="btn btn-danger mt-2">{{trans('fields.delete')}}</a>
            @endif
            @if(Perm::can(['order_batal']) && ($data->ordertype == 'DINEIN') && ($data->orderstatus == 'ADDITIONAL' || $data->orderstatus == 'COMPLETED' || $data->orderstatus == 'PAID'))
              <a href="" id="void" type="button" class="btn btn-danger mt-2">Batalkan Pesanan</a>
            @endif
            <a href="{{url('/order/meja/view')}}" type="button" id='back' class="btn btn-warning mt-2">{{trans('fields.back')}}</a>
          </div>
          <div class="float-right">
            <?php 
              $canSaveBtn = isset($data->id)
              ? ($data->orderstatus == 'ADDITIONAL' || $data->orderstatus == 'PROCEED' || $data->orderstatus == 'COMPLETED') && $data->orderpaid == null ? true : false
              : true 
            ?>
            @if(Perm::can(['order_simpan']) && $canSaveBtn)
              <a type="button" class="btn btn-primary mt-2 prosesOrder">{{trans('fields.proceed')}}</a>
            @endif
          </div>
        @else
          <div class="float-right">
            <a href="{{url('/order/meja/view')}}" type="button" id='back' class="btn btn-warning mt-2">{{trans('fields.back')}}</a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">{{ trans('fields.add') }} {{trans('fields.product')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <table class="table mb-4">
            <tbody>
              <tr>
                <td class="text-left">{{ trans('fields.prodCode') }}</td>
                <td class="text-primary">
                  <select id="showcasePopup" class="form-control form-control-sm showcasePopup">
                  </select>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="popDismiss" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="popSubmit" style="min-width: 75px;" class="btn btn-info btn-sm font-bold modal-add-row">Ubah</button>
      </div>
    </div>
  </div>
</div>

<table id="tabel" class="row-template d-none">
    @include('Order.subOrder')
</table>
@endsection

@section('js-body')
<script>
  let totalPrice = 0;
  $(document).ready(function (){
    //modal-tambah
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

    //VOID
    $('#void').on('click', function (e) {
      e.preventDefault();
      
      const url = "{{ url('order/batal') . '/' }}" + '{{$data->id}}';
      const title = 'Batalkan Pesanan';
      const pesan = 'Alasan batal?'
      gridDeleteInput2(url, title, pesan, null);
    });

    //Delete deleteOrder
    $('#deleteOrder').on('click', function (e) {
      e.preventDefault();
      
      const url = "{{ url('order/hapus') . '/' }}" + '{{$data->id}}';
      const title = 'Hapus Pesanan';
      gridDeleteInput3(url, title, null, function(callb){
        setTimeout(() => {
          window.location = "{{ url('/order/meja/view') }}";
        }, 2000);
      });
    });

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
        $('.prosesOrder').attr('dissabled');
        $('#orderProductForm').submit();
      }
    })
    
    @if(!isset($data->ordervoidedat) && empty($data->orderpaid))
      $('.productCard').on('click', function(){
        $('.modal-add-row').removeAttr('disabled');
        $('.showcase').addClass('d-none');
        $(".showcasePopup").empty();
        let productId = $(this).attr('data-id'),
          productName = $(this).attr('data-productName'),
          productprice = $(this).attr('data-productprice'),
          productpriceraw = $(this).attr('data-productpriceraw'),
          productpcid = $(this).attr('data-productpcid'),
          promoname = $(this).attr('data-promoname'),
          promoid = $(this).attr('data-promoid'),
          promodiscount = $(this).attr('data-promodiscount');

        setTimeout(() => {
          $("#addToTableProduct").attr("data-pProductText",productName);
          $("#addToTableProduct").attr("data-pProductPrice",productprice);
          $("#addToTableProduct").attr("data-pProductPriceRaw",productpriceraw);
          $("#addToTableProduct").attr("data-pId",productId);
          $("#addToTableProduct").attr("data-pPromo",promodiscount);
          $("#addToTableProduct").attr("data-pPromoId",promoid);

          $('#addToTableProduct').trigger('click');
        }, 0);





        $.ajax({
            type: "GET",
            url: "{{ url('api/product/detail') }}" + "/" + productId,
            success: function(data){
              if(data.status == 'success'){ 
                let item = data.data;
                let bodyPopup = {
                  'text' : item.productname,
                  'price' : item.productprice,
                  'priceRaw': item.productpriceraw,
                  'promo': item.promodiscount,
                  'promoText': item.promoname,
                  'promoEnd': item.promoend,
                };

                

              } else {
                toast({
                  type: data.status,
                  title: data.messages[0],
                  padding: '2em',
                });
              }
            },
            error: function(data){
              toast({
                type: 'error',
                title: 'Kesalahan. Tidak dapat memproses.',
                padding: '2em',
              });
            }
         });
      });
    @endif

    caclculatedOrder()
  });

  function setupTableGrid($targetContainer)
  {
    // Setups add grid. 
    $targetContainer.registerAddRow($('.row-template'), $('.add-row'));
    $targetContainer.on('row-added', function (e, $row){
      let rowProductText = $("#addToTableProduct").attr('data-pProductText'),
          rowProductPrice = $("#addToTableProduct").attr('data-pProductPrice'),
          rowProductPriceRaw = $("#addToTableProduct").attr('data-pProductPriceRaw'),
          rowId = $("#addToTableProduct").attr('data-pId'),
          rowPromo = $("#addToTableProduct").attr('data-pPromo'),
          rowPromoId = $("#addToTableProduct").attr('data-pPromoId'),
          rowProductType = $("#addToTableProduct").attr('data-pProductType') ?? "PO",
          qty = 1,
          // remark = $('#uiModalInstance').find('#productRemark').val(),
          tprice = qty*rowProductPrice;

      let promoTeks = '';
      if(rowPromo){
        promoTeks = '<span class="badge outline-badge-info"> Promo </span>&nbsp;'
      }

      $row.find('[id^=dtl][id$="[odproducttext]"]').html(promoTeks + rowProductText);
      $row.find('[id^=dtl][id$="[odprice]"]').html(formatter.format(rowProductPrice));
      $row.find('[id^=dtl][id$="[odtotalprice]"]').html(formatter.format(tprice));
      $row.find('[name^=dtl][name$="[odtotalprice]"]').val(tprice);
      $row.find('[name^=dtl][name$="[odproducttext]"]').val(rowProductText);
      $row.find('[name^=dtl][name$="[odproductid]"]').val(rowId);
      $row.find('[name^=dtl][name$="[odpromoid]"]').val(rowPromoId);
      $row.find('[name^=dtl][name$="[odqty]"]').val(qty);
      $row.find('[name^=dtl][name$="[odprice]"]').val(rowProductPrice);
      $row.find('[name^=dtl][name$="[odpriceraw]"]').val(rowProductPriceRaw);

      $row.find('[id^=dtl][id$="[odtype]"]').html(rowProductType);
      $row.find('[name^=dtl][name$="[odtype]"]').val(rowProductType);
      $row.find('[name^=dtl][name$="[odshowcaseid]"]').val("");

      window.setTimeout(() => {
        caclculatedOrder()        
      }, 0);
    })
    .on('row-removing', function (e, $row){
      $row.remove();
      caclculatedOrder();
    })
    .on('row-counterup', function(e, $row){
      let rQty = $row.find('[name^=dtl][name$="[odqty]"]');
      let min = rQty.attr('min') || false;
      
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

      caclculatedOrder();
    })
    .on('row-delivering', function (e, $row){
      let productId = $row.find('[name^=dtl][name$="[odproductid]"]').val(),
          productType = $row.find('[name^=dtl][name$="[odtype]"]');

        if(productType.val() == 'READYSTOCK'){
          // $('.modal-add-row').attr('disabled', 'disabled');
          $.ajax({
            type: "GET",
            url: "{{ url('api/product/showcase-code') }}" + "/" + productId,
            success: function(sData){
              if(sData.status == 'success'){
                $("#addToTableProduct").attr("data-pShowcaseId",sData.data[0]['id']);

                let productCodeChild = '';
                $.each( sData.data, function( key, value ) {
                  productCodeChild += "<option value='"+ value.id +"'>" + value.showcasecode + "</option>";
                });

                $('.showcasePopup').append(productCodeChild);
                
                showPopupOrder({}, function(){
                  let selected = $('#uiModalInstance').find('#showcasePopup').val();
                  $row.find('[name^=dtl][name$="[odshowcaseid]"]').val(selected);
                  $row.find('[id^=dtl][id$="[odshowcase]"]').html('Kd. Produksi:' + selected);
                });
              } else {
                productType.val("PO").change();
                toast({
                  type: sData.status,
                  title: sData.messages[0],
                  padding: '2em',
                });
              }
            }
          });
        } else {
          $row.find('[name^=dtl][name$="[odshowcaseid]"]').val(null);
          $row.find('[id^=dtl][id$="[odshowcase]"]').html('');
        }
      // caclculatedOrder();
    });
  }
  
  function caclculatedOrder(){
    let gridRow = $('#detailOrder').find('[id^=dtl][id$="[odproducttext]"]').closest('tr');
    totalPrice = 0;
    gridRow.each(function(){
      let price = $(this).find('[name^=dtl][name$="[odtotalprice]"]').val();
      totalPrice += Number(price);
    });
    $('#idTotal').html(formatter.format(totalPrice));
    $('[name="orderprice"]').val(totalPrice);
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