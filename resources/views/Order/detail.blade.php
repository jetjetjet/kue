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
          @if(isset($data->orderrefundid))
          <li>Kode Refund : <a style="color: inherit; text-decoration: inherit;" href="{{url('/pengeluaran/detail')}}/{{$data->orderrefundid}}"><b>{{$data->expensecode}}</b></a></li>
          @endif
        </ul>
    </div>
  @endif
  @if($data->orderstatus == "COMPLETED")
    <div class="alert alert-primary" role="alert">
      <strong>Pesanan Selesai</strong>
        <ul>
          <li>Diselesaikan Oleh : <b>{{$data->ordercompletedname}}</b></li>
          <li>Diselesaikan Pada : {{$data->ordercompleteddate}}</li>
        </ul>
    </div>
  @endif
  @if($data->orderstatus == "PAID")
    <div class="alert alert-info" role="alert">
      <strong>Pesanan Sudah Lunas</strong>
        <ul>
          <li>Nama Pelanggan : <b>{{$data->ordercustname}}</b></li>
          <li>Tanggal Perkiraan Selesai : {{$data->orderestdate}}</li>
        </ul>
    </div>
  @endif
  @if($data->orderstatus == "DP")
    <div class="alert custom-alert-1" role="alert">
      <strong>Pesanan Dibayar dimuka</strong>
        <ul>
          <li>Nama Pelanggan : <b>{{$data->ordercustname}}</b></li>
          <li>Tanggal Perkiraan Selesai : {{$data->orderestdate}}</li>
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
                      <th>{{trans('fields.product')}}</th>
                      <th>Tipe Pesanan</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Promo</th>
                      <th>Total</th>
                      <th>Catatan</th>
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
                  <div class="form-row">
                    <div class='col-12'>
                      <table class="table table-borderless">
                        <thead>
                          <tr>
                            <th style="width: 55% !important;" class="p-1 w-auto dtl">Nomor Pesanan</th>
                            <th class="col p-1 text-right dtl">{{$data->orderinvoice}}</th>
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
                            <tr class="border-top dp {{(isset($data->orderdp) && $data->odTypeCek['odcek'] || $data->orderstatus == 'DRAFT' && $data->odTypeCek['odcek'] ) ? '' : 'd-none'}}">                          
                                <?php 
                                  $lblDP = isset($data->orderdp) 
                                    ? number_format($data->orderdp,0)
                                    : 0;
                                ?>
                              <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0 dp">DP</th>
                              <th class="py-0 my-0 text-right dp">
                                <h4><b class='float-right'><p class="my-0 lbl" id="lblDP">{{ $lblDP }}</p></b></h4>
                              </th>
                            </tr>
                            <tr class="dp {{(isset($data->orderdp) && $data->odTypeCek['odcek'] || $data->orderstatus == 'DRAFT' && $data->odTypeCek['odcek'] ) ? '' : 'd-none'}}">
                              <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0 dp">Sisa Bayar</th>
                              <th class="py-0 my-0 text-right dp">
                                <?php 
                                  $lblSisa = isset($data->orderdp)
                                    ? number_format($data->orderprice - $data->orderdp - $data->orderdiscountprice)
                                    : 0;
                                  $sisa = isset($data->orderdp)
                                    ? $data->orderprice - $data->orderdp - $data->orderdiscountprice
                                    : 0;
                                ?>
                                <h4><b class='float-right'><p class="my-0 lbl" id="lblSisa">{{ $lblSisa }}</p></b></h4>
                              </th>   
                            </tr>                             
                            <tr class="border-top pd {{!($data->orderstatus == 'DP' || !$data->odTypeCek['odcek'] || $data->orderstatus == 'PAID' || $data->orderstatus == 'VOIDED' || $data->orderstatus == 'COMPLETED') ? 'd-none' : ''}}">
                                <?php 
                                  $lblPaid = isset($data->orderpaid) 
                                    ? number_format($data->orderpaidprice,0)
                                    : 0;
                                ?>
                              <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0">Bayar</th>
                              <th class="py-0 my-0 text-right">
                                <h4><b class='float-right'><p class="my-0 lbl" id="lblBayar">{{ $lblPaid }}</p></b></h4>
                              </th>
                            </tr>
                            <tr class="pd {{!($data->orderstatus == 'DP' || !$data->odTypeCek['odcek'] || $data->orderstatus == 'PAID' || $data->orderstatus == 'VOIDED' || $data->orderstatus == 'COMPLETED') ? 'd-none' : ''}}">
                              <th style="padding-left: 0.3rem; width: 55% !important;" class="py-0 my-0">Kembalian</th>
                              <th class="py-0 my-0 text-right">
                                <?php 
                                  $lblKembalian = $data->orderpaid
                                    ? isset($data->orderdiscountprice)
                                      ? number_format($data->orderpaidprice - ($data->orderprice - $data->orderdiscountprice) + $data->orderdp)
                                      : number_format($data->orderpaidprice - $data->orderprice + $data->orderdp )
                                    : 0;
                                ?>
                                <h4><b class='float-right'><p class="my-0 lbl" id="lblKembalian">{{ $lblKembalian }}</p></b></h4>
                              </th>
                            </tr>    

                        </thead>
                      </table>
                    </div>
                  </div>
                  <form id="orderMenuForm" method="post" novalidate action="{{url('/order/bayar')}}/{{$data->id}}">
                    <input type="hidden" id="orderprice" value="{{$data->orderprice}}">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="username" id="name" value="{{ session('username') }}" />
                    @if($data->orderstatus == 'DRAFT' || $data->orderstatus == 'DP')
                      <div class="form-row mt-2">
                        @if($data->orderstatus == 'DRAFT')
                          <div class='col-md-5 col-sm-6 xs-6 mt-2'>
                            <h4>Nama Pelanggan</h4>
                          </div>
                          <div class='col-md-7 col-sm-6 xs-6 mt-1'>
                            <input type="text" class="form-control" required name="ordercustname" id="custs" value="{{ old('ordercustname', $data->ordercustname) }}" placeholder="Nama Pelanggan">
                          </div>
                        @endif
                        @if($data->orderstatus == 'DP')
                          <input type="hidden" class="form-control" {{($data->orderstatus == 'DP') ? 'readonly' : ''}} required name="ordercustname" id="custs" value="{{ old('ordercustname', $data->ordercustname) }}" placeholder="Nama Pelanggan">
                        @elseif($data->odTypeCek['odcek'])
                          <div class='col-md-5 col-sm-6 xs-6 mt-2'>
                            <h4>Sistem Pembayaran</h4>
                          </div>
                          <div class='col-md-7 col-sm-6 xs-6 mt-1'>
                            <select class="form-control mousetrap" id="status" name="orderstatus">
                              <option value="DP" {{ old('orderstatus', $data->orderstatus) == 'DP' ? ' selected' : '' }}> DP</option>
                              <option value="PAID" {{ old('orderstatus', $data->orderstatus) == 'PAID' ? ' selected' : '' }}> Lunas</option>
                            </select>  
                          </div>
                        @endif
                        <div class='col-md-5 col-sm-6 xs-6 mt-2'>
                          <h4>Jenis Pembayaran</h4>
                        </div>
                        <div class='col-md-7 col-sm-6 xs-6 mt-1'>
                          <select class="form-control mousetrap" id="type" name="orderpaymentmethod">
                            <option value="Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Tunai' ? ' selected' : '' }}> Tunai</option>
                            <option value="Non-Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Non-Tunai' ? ' selected' : '' }}> Non-Tunai</option>
                          </select>  
                        </div>
                        @if($data->orderstatus == 'DP')
                        <input type="hidden" value="{{$data->orderdp}}" class="form-control text-right mousetrap input" required name="orderdp" id="dp" placeholder="DP">
                        <input type="hidden" readonly name="orderstatus" class="form-control" required value="COMPLETED">
                        <input type="hidden" class="d-none" name="orderestdate" id='date' value='{{$data->orderestdate}}' placeholder="Tanggal">  
                        <input type="hidden" readonly id="sisa" value="{{$sisa}}" name="orderremainingpaid" class="form-control input" required>               
                        @elseif($data->odTypeCek['odcek'])
                          <div class='col-md-5 col-sm-6 xs-6 mt-2'>
                            <h4>Perkiraan Selesai</h4>
                          </div>
                          <div class='col-md-7 col-sm-6 xs-6 mt-1'>
                            <input type="date" class="form-control mousetrap flatpickr text-right flatpickr-input date" id="date" required name="orderestdate" placeholder="Tanggal">
                          </div>
                          <div class='col-md-5 col-sm-6 xs-6 mt-2 dp'>
                            <h4>Nominal DP</h4>
                          </div>
                          <div class='col-md-7 col-sm-6 xs-6 mt-1 dp'>
                            <input autofocus type="number" class="form-control text-right mousetrap input" required name="orderdp" id="dp" placeholder="DP">
                          </div>
                          <input type="hidden" readonly id="odValid" class="form-control" required value="true">
                          <input type="hidden" readonly name="orderremainingpaid" class="form-control input" required>
                        @else
                          <input type="hidden" readonly name="orderstatus" class="form-control" required value="COMPLETED">
                          <input type="hidden" class="d-none" id='date' value='1' placeholder="Tanggal">
                          <input type="hidden" class="d-none" id='dp'>
                        @endif
                        <div class="col-md-5 col-sm-6 xs-6 mt-2 pd {{!($data->orderstatus == 'DP' || !$data->odTypeCek['odcek']) ? 'd-none' : ''}}">
                          <h4>Diskon</h4>
                        </div>
                        <div class="col-md-7 col-sm-6 xs-6 mt-1 pd {{!($data->orderstatus == 'DP' || !$data->odTypeCek['odcek']) ? 'd-none' : ''}}">
                          <input type="number" class="form-control text-right mousetrap input" required name="orderdiscountprice" id="diskon" placeholder="Diskon">
                        </div>
                        <div class="col-md-5 col-sm-6 xs-6 mt-2 pd {{!($data->orderstatus == 'DP' || !$data->odTypeCek['odcek']) ? 'd-none' : ''}}">
                          <h4>Nominal Bayar</h4>
                        </div>
                        <div class="col-md-7 col-sm-6 xs-6 mt-1 pd {{!($data->orderstatus == 'DP' || !$data->odTypeCek['odcek']) ? 'd-none' : ''}}">
                          <input autofocus type="number" class="form-control text-right mousetrap mb-2 input" required name="orderpaidprice" id="bayar" placeholder="Jumlah Uang"> 
                        </div>                                 
                    @elseif($data->orderstatus == 'PAID')
                    <input type="hidden" readonly id="sisa" value="{{$sisa}}" name="orderremainingpaid" class="form-control input" required>
                      <div class='float-right mt-4'>
                          <button type="button" id="completeOrder" class="btn-lg btn btn-info">{{trans('fields.complete')}}</button>
                      </div>
                    @endif
                    </div>
                  </form>  
                  <form method="post" id="completeform" novalidate action="{{url('/order/selesai')}}/{{$data->id}}">
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
          <div class="float-left">
            @if(Perm::can(['order_batal']) && isset($data->id) && !($data->orderstatus == "VOIDED" || $data->orderstatus == "DRAFT" || $data->orderstatus == "COMPLETED"))
              <a id="void" type="button" class="btn btn-danger mt-2">Batalkan Pesanan</a>
            @elseif($data->orderstatus == "DRAFT")
              <a href="" id="deleteOrder" type="button" class="btn btn-danger mt-2">{{trans('fields.delete')}}</a>
            @endif
            <a href="{{url('/')}}" id="back" type="button" class="btn btn-warning mt-2">Kembali</a>
          </div>
          <div class="float-right">
            @if($data->orderstatus != 'DRAFT' && $data->orderstatus != 'VOIDED')
              <button id="print" class="btn btn-secondary mt-2">Cetak</button>
            @endif
            @if(!($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID' || $data->orderstatus == 'COMPLETED'))
              @if(Perm::can(['order_updatePesanan']))
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
      let price = $("#orderprice").val();
      let pay = $('#bayar').val();
      let diskon = $("#diskon").val();
      let dp = $("#dp").val();
      let tgl = $("#date").val()
      let cust = $("#custs").val()
      let valid = $('#odValid').val();   
      let change = Number(diskon) + (Number(pay) - Number(price)) + Number(dp); 
      let dPrice = Number(price) - Number(dp) - Number(diskon)  
      let discPrice = Number(price) - Number(diskon)
      let lbl = function(){
        $('#lblBayar').html(formatter.format(pay));
        $("#lblSisa").html(formatter.format(dPrice));
        $("#lblDP").html(formatter.format(dp));
        $("#sisa").val(dPrice)
      }
      
      if(Number(pay)<0){
        $('#lblKembalian').html("error");
        $('#drawer').attr('disabled', true);
      }else if(Number(diskon) >= Number(price) || Number(dp) >= Number(price) || Number(change) < 0){
        lbl()
        $('#lblKembalian').html(0);
        $('#drawer').attr('disabled', true);
      }else if(Number(change) >= 0 && tgl && (cust||!valid)){
        lbl()
        $("#lblKembalian").html(formatter.format(change));
        $('#drawer').removeAttr('disabled');
      }else if(!tgl || !cust){
        lbl()
        $("#lblKembalian").html(formatter.format(change));
        if(!tgl)
          $("#date").css('border-color', '#FF0000');
        if(!cust)
          $("#custs").css('border-color', '#FF0000');
      }else{
        $("#lblDP").html(formatter.format(0));
        $('#lblKembalian').html(0);
        $('#lblBayar').html(0);
        $('#drawer').attr('disabled', true);
      }

      
      if(Number(diskon)<0){
        $("#lblDiskon").html("error");
        $('#drawer').attr('disabled', true);
      } else if( (Number(price) - Number(dp)) <= Number(diskon) ){
        lbl()
        $("#lblDiskon").html(formatter.format(diskon));
        $("#lblGranTotal").html("Error");
        $('#drawer').attr('disabled', true);
      } else if(Number(diskon)){
        $("#lblDiskon").html(formatter.format(diskon));
        $("#lblGranTotal").html(formatter.format(discPrice))
        lbl()
      } else {
        $("#lblGranTotal").html(formatter.format(price));
        $("#lblDiskon").html("-");
      }  

      
      if(Number(dp)<0){
        $("#lblSisa").html("Error");
        $('#drawer').attr('disabled', true);
      } else if( Number(price) <= Number(dp) ){
        lbl()
        $("#lblSisa").html("Error");
        $("#sisa").val(null)
        $('#drawer').attr('disabled', true);
      } else if(Number(dp) && !Number(pay) && valid == "true" && tgl && cust){
        lbl()
        $('#drawer').removeAttr('disabled');
      } else if(Number(dp) && !Number(pay)){
        if(!tgl)
          $("#date").css('border-color', '#FF0000');
        if(!cust)
          $("#custs").css('border-color', '#FF0000');
        lbl()
      } else if (!dp) {
        $("#lblDP").html(0);
        $("#lblSisa").html(0);
        $("#sisa").val(null)
      }  
    }


  $(document).ready(function (){


    $('#deleteOrder').on('click', function (e) {
      e.preventDefault();
      
      const url = "{{ url('order/hapus') . '/' }}" + '{{$data->id}}';
      const title = 'Hapus Pesanan';
      gridDeleteInput3(url, title, null, function(callb){
        setTimeout(() => {
          window.location = "{{ url('/') }}";
        }, 2000);
      });
    });

    $('#void').on('click', function (e) {
      e.preventDefault();
      
      const url = "{{ url('order/batal') . '/' }}" + '{{$data->id}}';
      const title = 'Batalkan Pesanan';
      const pesan = 'Alasan batal?'
      gridDeleteInput2(url, title, pesan, null);
    });

    //hotkey
    let hotkey = function(){
      let draw = $('#drawer').attr('disabled')
      if(!draw){
        $('#drawer').trigger('click') 
      }else{
        Swal.fire({
            type: 'error',
            title: "Kolom kosong/Nominal tidak sesuai",
            showConfirmButton: false,
            timer: 1000
          })
      }
    }
      Mousetrap.bind('enter', function() {
        hotkey();
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
            $('#dp').focus()
            Mousetrap.bind('enter', function() {
              hotkey();
            });
        });
    //endmodalkey

    //Cetak
    $('#print').on('click', function () {
      var user = $("#name").val();
      Swal.fire('Sedang Diproses')
      Swal.showLoading()
      $.ajax({
      url: "{{url('/order/bayar/cetak')}}/"+"{{$data->id}}",
      type: "post",
      data: {username: user},
      success: function(result){
        //console.log(result);
        var msg = result.messages[0];
        if(result.status == 'success'){
          Swal.fire({
            type: result.status,
            title: msg,
            showConfirmButton: false,
            timer: 1500
          })
        }else{
          Swal.fire({
            type: result.status,
            title: msg,
            showConfirmButton: false,
            timer: 1500
          })
        }      
        
      },
      error:function(error){
        var msg = result.messages[0]
        Swal.fire({
            type: result.status,
            title: msg,
            showConfirmButton: false,
            timer: 1500
        })
      }
      })
     
      
    });
    //Bayar
    $('#drawer').on('click', function () {
      var price = $("#orderprice").val();
      var pay = $('#bayar').val();
      let diskon = $("#diskon").val();
      let dp = $("#dp").val();
      let valid = $('#odValid').val();
      var change = Number(pay) - (Number(price) - Number(diskon)) + Number(dp);
      Swal.fire('Sedang Diproses')
      Swal.showLoading()
      $.ajax({
      url: "{{url('/open/drawer') }}",
      type: "post",
      success: function(result){
        //console.log(result);
        var msg = result.messages[0];
        if(result.status == 'success'){
          if(change == 0 || dp && valid){
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

    
    $('#completeOrder').on('click', function(){
      $('#completeform').submit();
    })

    $('#buttOut').on('click', function(){
      $('#orderMenuForm').submit();
    })

    $('#prosesOrder').on('click', function(){
      $('#orderMenuForm').submit();
    })

    $('#custs').on('keyup', function(){
      if(this.value){
        $("#custs").css('border-color', '')
      }else{
        $("#custs").css('border-color', '#FF0000')
      }
      payAndchange();
    })
    $('#custs').on('change', function(){
      let str = this.value
      str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
      });
      $("#custs").val(str)
    })

    $('#status').on('change', function(){
      let price = $("#orderprice").val();
      if(this.value == "DP"){
        $('.dp').removeClass('d-none')
        $('.pd').addClass('d-none')            
      }else{
        $('.pd').removeClass('d-none')
        $('.dp').addClass('d-none')        
      }
      $('#lblDiskon').html("-"); 
      $('.lbl').html("0");
      $('.input').val("")
      $('#drawer').attr('disabled', true);
      $("#lblGranTotal").html(formatter.format(price));
    })

    $('#bayar').on('keyup',function(){
      payAndchange();
    });

    $('#diskon').on('keyup',function(){
      payAndchange();
    });

    $('#dp').on('keyup',function(){
      payAndchange();
    });

    //disable enter form
    $('#orderMenuForm').on('keyup keypress', function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });
    //e

    flatpickr($('#date'), {
        dateFormat: "d-m-Y",
        altInput: false,
        altFormat: "Y-m-d",
        minDate: "today",
        defaultDate: "{{Carbon\Carbon::now()}}",
        position: "above",
        onChange: function(){
          payAndchange();
          $("#date").css('border-color', '')
        }
      });

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

            return data.odproducttext + prm;
          }
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            let odtype = data.odtype == "READYSTOCK"
            ? data.showcasecode
              ? "{{trans('fields.readyStock')}} - "+ data.showcasecode
              : "{{trans('fields.readyStock')}}"
            : "{{trans('fields.preOrder')}}"

            return odtype;
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
      ]
    }); 
  })
</script>
@endsection