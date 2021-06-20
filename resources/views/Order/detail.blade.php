@extends('Layout.layout-form')

@section('breadcumb')
<style>
 .table{
  margin-bottom: 0 !important;
 }

 .table > thead > tr > th {
  color: #212529;
 }
</style>
  <div class="title">
    <h3>Order</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/order').'/'.$data->id }}">Order</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Pembayaran</a></li>
  </ol>
@endsection

@section('content-form')
<section class="grid">
  @if(isset($data->ordervoidedat))
    <div class="alert alert-danger" role="alert">
      <strong>Pesanan Dibatalkan!</strong>
        <ul>
          <li>Dibatalkan Oleh: <b>{{$data->ordervoidedusername}}</b></li>
          <li>Dibatalkan Pada: {{$data->ordervoidedat}}</li>
          <li>Alasan: {{$data->ordervoidreason}}</li>
        </ul>
    </div>
  @endif
  
  @if(($data->orderstatus == 'ADDITIONAL' || $data->orderstatus == 'PROCEED')  && $data->ordertype == 'DINEIN')
    <div class="alert alert-warning" role="warning">
      <strong>Pesanan Masih Diproses!</strong>
        <ul>
          <li>Pembayaran tidak bisa dilanjutkan jika masih ada pesanan yang masih diproses.</li>
        </ul>
    </div>
  @endif
  <div class="row">
    <div class="col-lg-8 col-sm-12">
      <div class="widget-content widget-content-area br-6">
        <div class="row">
          <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing pb-1">
            <div class="statbox">
              <div class="widget-content">
                <div class="table-responsive">
                  <h3 class="mt-2 mb-10" style="font-weight: 300; text-align: left; padding-left: 1.8rem;">Detail Pesanan</h3>
                  <table id=grid class="table table-bordered mb-20">
                    <thead>
                      <th>Menu</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Promo</th>
                      <th>Total</th>
                      <th>Catatan</th>
                      @if(!$data->orderpaid)
                        <th>Status Pesanan</th>
                      @endif
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-sm-12">
      <div class="widget-content widget-content-area br-6">
          <div class="row">
            <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing pb-1">
              <div class="statbox">
                <div class="widget-content">
                  @if($data->ordertype == 'DINEIN')
                    <div class="form-row">
                      <div class='col-12'>
                        <h3 style="color:#1b55e2"><b>{{$data->orderboardtext}}</b></h3>
                      </div>
                    </div>
                  @endif
                  <div class="form-row">
                    <div class='col-12'>
                      <table class="table table-borderless">
                        <thead>
                          <tr>
                            <th style="width: 55% !important;" class="p-1 w-auto dtl">Nomor Pesanan</th>
                            <th class="col p-1 text-right dtl">{{$data->orderinvoice}}</th>
                          </tr>
                          <tr>
                            <th style="width: 55% !important;" class="p-1 w-auto">Jenis Pesanan</th>
                            <th class="col p-1 text-right">{{$data->ordertype == 'DINEIN' ? 'Makan Ditempat' : 'Bungkus' }}</th>
                          </tr>
                          <tr>
                            <th style="width: 55% !important;" class="p-1 w-auto">Tgl. Pesanan</th>
                            <th class="col p-1 text-right">{{$data->orderdate }}</th>
                          </tr>
                          @if(isset($data->orderpaiddate))
                            <tr>
                              <th style="width: 55% !important;" class="p-1 w-auto">Tgl. Pembayaran</th>
                              <th class="col p-1 text-right">{{$data->orderpaiddate }}</th>
                            </tr>
                          @endif
                          @if(isset($data->orderpaymentmethod))
                            <tr>
                              <th style="width: 55% !important;" class="p-1 w-auto">Jenis Pembayaran</th>
                              <th class="col p-1 text-right">{{$data->orderpaymentmethod }}</th>
                            </tr>
                          @endif
                        </thead>
                      </table>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class='col-12'>
                      <table class="table table-borderless" style="background-color: linen;">
                        <thead>
                          <tr>
                            <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0">Total</th>
                            <th class="py-0 my-0 text-right">
                              <h4><b class='float-right'><p class="my-0" id="price">{{ number_format($data->orderprice,0) }}</p></b></h4>
                            </th>
                          </tr>
                          <tr>
                            <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0">Diskon</th>
                            <th class="py-0 my-0 text-right">
                              <?php 
                                $lblDiskon = isset($data->orderdiscountprice) 
                                  ? number_format($data->orderdiscountprice,0)
                                  : "-";
                              ?>
                              <h4><b class='float-right'><p class="my-0" id="lblDiskon">{{ $lblDiskon }}</p></b></h4>
                              <!-- <input type="number" class="form-control text-right mousetrap" value="" name="orderdiscountprice" id="diskon" placeholder="Diskon"> -->
                            </th>
                          </tr>
                          <tr>
                            <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0">Grand Total</th>
                            <th class="py-0 my-0 text-right">
                              <?php 
                                $lblGranTotal = isset($data->orderdiscountprice) 
                                  ? number_format($data->orderprice - $data->orderdiscountprice,0)
                                  : number_format($data->orderprice,0);
                              ?>
                              <h4><b class='float-right'><p id="lblGranTotal" class="my-0">{{ $lblGranTotal }}</p></b></h4>
                            </th>
                          </tr>
                          <tr class="border-top">
                              <?php 
                                $lblPaid = isset($data->orderpaid) 
                                  ? number_format($data->orderpaidprice,0)
                                  : 0;
                              ?>
                            <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0">Bayar</th>
                            <th class="py-0 my-0 text-right">
                              <h4><b class='float-right'><p class="my-0" id="lblBayar">{{ $lblPaid }}</p></b></h4>
                            </th>
                          </tr>
                          <tr>
                            <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0">Kembalian</th>
                            <th class="py-0 my-0 text-right">
                              <?php 
                                $lblKembalian = $data->orderpaid
                                  ? isset($data->orderdiscountprice)
                                    ? number_format($data->orderpaidprice - ($data->orderprice - $data->orderdiscountprice),0)
                                    : number_format($data->orderpaidprice - $data->orderprice)
                                  : 0;
                              ?>
                              <h4><b class='float-right'><p class="my-0" id="lblKembalian">{{ $lblKembalian }}</p></b></h4>
                            </th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
                  <form id="orderMenuForm" method="post" novalidate action="{{url('/order/bayar')}}/{{$data->id}}">
                    <input type="hidden" id="afterPrice" value="{{$data->orderprice}}">
                    <input type="hidden" id="startPrice" value="{{$data->orderprice}}">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="username" id="name" value="{{ session('username') }}" />
                    @if($data->orderstatus == 'COMPLETED' || ($data->ordertype == 'TAKEAWAY' && !($data->orderstatus == 'PAID' || $data->orderstatus == 'VOIDED')))
                      <div class="form-row mt-2">
                        <div class='col-md-5 col-sm-6 xs-6 mt-2'>
                          <h4>Jenis Pembayaran</h4>
                        </div>
                        <div class='col-md-7 col-sm-6 xs-6'>
                          <select class="form-control mousetrap" id="type" name="orderpaymentmethod">
                            <option value="Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Tunai' ? ' selected' : '' }}> Tunai</option>
                            <option value="Non-Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Non-Tunai' ? ' selected' : '' }}> Non-Tunai</option>
                          </select>  
                        </div>
                        <div class='col-md-5 col-sm-6 xs-6 mt-2'>
                          <h4>Diskon</h4>
                        </div>
                        <div class='col-md-7 col-sm-6 xs-6 mt-1'>
                          <input type="number" class="form-control text-right mousetrap" required name="orderdiscountprice" id="diskon" placeholder="Diskon">
                        </div>
                        <div class='col-md-5 col-sm-6 xs-6 mt-2'>
                          <h4>Nominal Bayar</h4>
                        </div>
                        <div class='col-md-7 col-sm-6 xs-6 mt-1'>
                          <input autofocus type="number" class="form-control text-right mousetrap mb-2" required name="orderpaidprice" id="bayar" placeholder="Jumlah Uang"> 
                        </div>
                      </div>
                    @else
                      <input type="hidden" id="bayar" value="-1" />
                    @endif
                  </form>
                  <form id="miniform" method="post" novalidate action="{{url('/order/bayar/cetak')}}/{{$data->id}}">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="username" id="name" value="{{ session('username') }}" />
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>

  <!-- TOmbol Bawah -->
  <div class="statbox">
    <div id="fixbot" class="row fixed-bottom">
      <div class="col-sm-12 ">
        <div class="widget-content widget-content-area" style="padding:10px">
          <div class="float-right">
            <a href="{{url('/order/meja/view')}}" id="back" type="button" class="btn btn-warning mt-2">Kembali</a>
            @if($data->orderstatus == 'PAID')
              <button id="print" class="btn btn-success mt-2">Cetak</button>
            @endif
            @if(!($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID'))
              @if($data->ordertype == 'TAKEAWAY' || Perm::can(['order_pelayan']))
              <a href="{{ url('/order').'/'.$data->id }}" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah Pesanan</a>
              @endif
              <!-- <a href="" type="button" id="drawer" class="btn btn-success mt-2">Buka Laci</a> -->
              @if(Perm::can(['order_pembayaran']))
                <button disabled id="drawer" class="btn btn-primary mt-2">&nbsp;&nbsp;&nbsp;Bayar&nbsp;&nbsp;&nbsp;</button>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" data-keyboard="false" id="konfirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><b>Konfirmasi</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4><b>Apakah Pembayaran Sudah Sesuai?</b></h4>
        <p class="modal-text">Silahkan cek uang di laci, Jika uang untuk kembalian sudah mencukupi, Lanjutkan Cetak</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button class="btn btn-danger mt-2" data-dismiss="modal">Batalkan</button>
        <button type="button" id="prosesOrder" class="btn btn-primary mt-2">Cetak</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" data-keyboard="false" id="withoutPrint">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><b style="color: #e7515a;">Printer Tidak Terhubung</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4><b>Apakah anda ingin melanjutkan pembayaran?</b></h4>
        <p class="modal-text">Data akan tersimpan tanpa cetak, sebelum melanjutkan, cek kembalian dahulu</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button class="btn btn-danger mt-2" data-dismiss="modal">Batalkan</button>
        <button type="button" id="buttOut" class="btn btn-primary mt-2">Simpan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js-form')
<script>
  let payAndchange = function()
    {
      let sPrice = $("#startPrice").val();
      let price = $("#afterPrice").val();   
      let pay = $('#bayar').val();
      let diskon = $("#diskon").val();
      let change = + Number(diskon) + (Number(pay) - Number(sPrice))
      
      if(Number(diskon) >= Number(sPrice) || Number(change) < 0){
        $('#lblKembalian').html(0);
        $('#lblBayar').html(formatter.format(pay));
        $('#drawer').attr('disabled', true);
      }else if(Number(change) >= 0){
        $("#lblKembalian").html(formatter.format(change));
        $('#lblBayar').html(formatter.format(pay));
        $('#drawer').removeAttr('disabled');
      } else {
        $('#lblKembalian').html(0);
        $('#lblBayar').html(0);
        $('#drawer').attr('disabled', true);
      }
    }

    let disChange = function()
    {
      let sPrice = $("#startPrice").val();
      let diskon = $("#diskon").val();
      let discPrice = Number(sPrice) - Number(diskon)

      if( Number(sPrice) < Number(diskon) ){
        $("#lblGranTotal").html("Error");
        $("#lblDiskon").html(formatter.format(diskon));
        // $("#afterPrice").val(Number(sPrice));
        $('#drawer').attr('disabled', true);
      } else if(Number(diskon)){
        $("#lblGranTotal").html(formatter.format(discPrice));
        $("#lblDiskon").html(formatter.format(diskon));
        // $("#afterPrice").val(Number(discPrice));
      } else {
        $("#lblGranTotal").html(formatter.format(sPrice));
        $("#lblDiskon").html("-");
        // $("#afterPrice").val(Number(sPrice));
      }  
    }


  $(document).ready(function (){
    //hotkey
      Mousetrap.bind('enter', function() {
        let sPrice = $("#startPrice").val(); 
        let pay = $('#bayar').val();
        let diskon = $("#diskon").val();
        let change = Number(sPrice) - Number(diskon)
        if(Number(pay) == -1){
          alert('Pesanan Belum selesai')
        }else if(Number(pay) == 0){
          alert('Masukkan jumlah uang')
        }else if(Number(pay) < Number(change)){
          alert('Jumlah Uang tidak mencukupi')
        }else{
          $('#drawer').trigger('click')
        }
      });
    //endhotkey
    //hotkeymodal
        $('#withoutPrint').on('shown.bs.modal', function() { 
          Mousetrap.bind('backspace', function(){
            $('#withoutPrint').modal('hide')
          });
          Mousetrap.bind('enter', function() {
            $('#buttOut').trigger('click')
          })
          $('#buttOut').focus()
        });

        $('#konfirm').on('shown.bs.modal', function() { 
          Mousetrap.bind('backspace', function(){
            $('#konfirm').modal('hide')
          });
          Mousetrap.bind('enter', function() {
            $('#prosesOrder').trigger('click')
          })
          $('#prosesOrder').focus()
        });

        $(window).on('hidden.bs.modal', function() { 
          Mousetrap.unbind('backspace')
            $('#bayar').focus()
            Mousetrap.bind('enter', function() {
              let sPrice = $("#startPrice").val(); 
              let pay = $('#bayar').val();
              let diskon = $("#diskon").val();
              let change = Number(sPrice) - Number(diskon)
              if(Number(pay) == 0){
                alert('Masukkan jumlah uang')
              }else if(Number(pay) < Number(change)){
                alert('Jumlah Uang tidak mencukupi')
              }else{
                $('#drawer').trigger('click')
              }
            });
        });
    //endmodalkey

    //Cetak

    $('#drawer').on('click', function () {
      var price = $("#startPrice").val();
      var pay = $('#bayar').val();
      let diskon = $("#diskon").val();
      var change = Number(pay) - (Number(price) - Number(diskon));
      Swal.fire('Sedang Diproses')
      Swal.showLoading()
      $.ajax({
      url: "{{url('/open/drawer') }}",
      type: "post",
      success: function(result){
        //console.log(result);
        var msg = result.messages[0];
        if(result.status == 'success'){
          if(change == 0){
            $('#orderMenuForm').submit();
          }else{
            $('#konfirm').modal('show');
          }
        }else{
          $('#withoutPrint').modal('show');
        }      
        Swal.hideLoading()  
        Swal.clickConfirm()
      },
      error:function(error){
        Swal.hideLoading()
        Swal.clickConfirm()
      }
      })
     
      
    });

    $('#print').on('click', function(){
      $('#miniform').submit();
    })

    $('#buttOut').on('click', function(){
      $('#orderMenuForm').submit();
    })
    // $('#bayar').setupMask(0);

    $('#bayar').on('keyup',function(){
      payAndchange();
      disChange();
    });
    $('#diskon').on('keyup',function(){
      payAndchange();
      disChange();
    });

    $('#prosesOrder').on('click', function(){
      $('#orderMenuForm').submit();
    })

    //disable enter form
    $('#orderMenuForm').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
  });
    //e


    let grid = $('#grid').DataTable({
      ajax: {
        url: "{{ url('order/detail/grid').'/' }}" + '{{$data->id}}',
        dataSrc: ''
      },
      dom: '<"row"<"col-md-12"<"row" > ><"col-md-12"rt> <"col-md-12"<"row">> >',

      "paging": false,
      "ordering": false,
      columns: [
        {
          data: null,
          render: function(data, type, full, meta){
            let prm = data.odpromoid
             ? '&nbsp;<span class="badge outline-badge-info"> Promo </span>'
             : '';

            return data.odmenutext + prm;
          }
        },
        { 
          data: 'odqty',
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            let promo = Number(data.odispromo) ? data.odpriceraw : data.odprice ;
            return formatter.format(promo);
          }
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            let promo = Number(data.odispromo) ? '@' + formatter.format(data.promodiscount) : '-' ;
            return promo;
          }
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            // let totalPromo = odispromo ? data. : ;
            return formatter.format(data.odtotalprice);
          }
        },
        { 
          data: 'odremark',
        },
        @if(!$data->orderpaid)
        {
          data: null,
          render: function(data, type, full, meta){
            let textDeliv = data.oddelivertext == "Sedang Diproses"
              ? '<span class="badge badge-danger"> Sedang Diproses </span>'
              : '<span class="badge badge-info"> Sudah Diantar </span>';
            return textDeliv;
          }
        }
        @endif
      ]
    });

    
    const toast = swal.mixin({
      toast: true,
      position: 'center',
      showConfirmButton: false,
      timer: 2000,
      padding: '2em'
    });
  

    inputSearch('#cariMeja', "{{ Url('/meja/cariTersedia') }}", 'resolve', function(item) {
      return {
        text: item.text,
        id: item.id
      }
    });

    $('#cariMeja').on('select2:select', function (e) {
      $('#cariMeja').attr('data-has-changed', '1');
    });

    $('#orderType').on('change',function(){
      let val = $(this).val();
      if(val == "TAKEAWAY"){
        $('#divMeja').addClass('d-none')
      } else {
        $('#divMeja').removeClass('d-none')
      }
    });

    // Loop over them and prevent submission
    let validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })
</script>
@endsection