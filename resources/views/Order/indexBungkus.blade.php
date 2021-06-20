@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>User</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Transaksi</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Daftar Pesanan</a></li>
    <li class="breadcrumb-item active" aria-current="page"><a href="javascript:void(0);">Bungkus</a></li>
  </ol>
@endsection

@section('content-table')
  <div class="widget-content widget-content-area br-6">
    <div class="mb-4">
      <h3>Pesanan Dibungkus (7 Hari Terakhir)</h3>
    </div>
    <fieldset>
      <div class="form-row ml-4">
        <div class="form-group col-2">
          <input class="form-control form-control-sm" id="periodeLog" name="periodeLog" type="text" placeholder="Periode Log">
        </div>
        <div class="form-group input-group col-2 pr-0">
          <input id="filterText" class="form-control form-control-sm" type="text" placeholder="Cari" style="border-top-right-radius: 0px!important; border-bottom-right-radius: 0px!important;" disabled>
          <!-- <div class="input-group-append">
            <span class="input-group-text" id="basic-addon2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></span>
          </div> -->
        </div>
        <div class="form-group col-1 pl-0">
          <select id="filterColumn" class="form-control form-control-sm" style="border-top-left-radius: 0px!important; border-bottom-left-radius: 0px!important;">
            <option value=""></option>
            <option value="orderinvoice">No. Invoice</option>
            <option value="orderprice">Total Harga</option>
            <!-- <option value="orderstatus">Meja</option> -->
          </select>
        </div>
        <div class="form-group col-2">
          <button class="btn btn-danger" id="reset">Reset</button>
          <button class="btn btn-success" id="apply">Terapkan</button>
        </div>
      </div>
    </fieldset>
    <div class="table-responsive">
      <table id="gridtakeaway" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No.Invoice</th>
            <th>Tipe pesanan</th>
            <th>tanggal</th>
            <th>total</th>
            <th>status</th>
            <th class="no-content"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
          <th>No.Invoice</th>
            <th>Tipe Pesanan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
@endsection

@section('js-table')
  <script>
    $(document).ready(function (){
      let fDate = flatpickr($('#periodeLog'), {
        mode: "range",
        altinput: true,
        altformat: "Y-m-d",
        dateFormat: "d-m-Y",
        maxDate: "today",
        // maxRange: 10,
        onChange: function (selectedDates, dateStr, instance) {
          if (selectedDates.length > 1) {
            let range = instance.formatDate(selectedDates[1], 'U') - instance.formatDate(selectedDates[0], 'U');
            range = range / 86400;

            if(range > 30)
            {
              alert("Maksimal 30 hari!");
              instance.clear()
            }
          }
        },
        // defaultDate: ["2016-10-10", "2016-10-20"]
      });

      let grid2 = $('#gridtakeaway').DataTable({
        ajax: {
            url: "{{ url('order/index/grid/takeaway') }}",
            "data": function(dt){
              return $.extend( {}, dt, {
                "filterDate" : $('#periodeLog').val(),
                'filterText': $('#filterText').val(), 
                'filterColumn': $('#filterColumn').val()
              } );
            },
            // dataSrc:''
          },
        dom: 
          '<"row"<"col-md-12"<"row"<"col-md-6"B> > >' +
          '<"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: []
        },
        "processing": true,
        "serverSide": true,
        "oLanguage": {
          "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
          "sInfo": "Halaman _PAGE_ dari _PAGES_",
          "sSearch": '<i data-feather="search"></i>',
          "sSearchPlaceholder": "Cari...",
          "sLengthMenu": "Hasil :  _MENU_",
          "sInfoEmpty": "Tidak ada data ditemukan",
          "sInfoFiltered": "(dari jumlah total _MAX_ data)",
          "sZeroRecords": "Tidak ada data ditemukan"
        },
        "stripeClasses": [],
        "lengthMenu": [10, 20, 50],
        "pageLength": 8,
        "order": [],
        columns: [
          { 
            data: 'orderinvoice',
            searchText: true
          },
          { 
              data: 'ordertypetext',
              searchText: true
          },
          { 
              data: 'orderdate',
              searchText: true
          },
          { 
            data: null,
            render: function(data, type, full, meta){
            return formatter.format(data.orderprice);
            }
          },
          { 
              data: 'orderstatuscase',
              searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
                return '<a href="#" title="Detail" class="gridDetail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search p-1 br-6 mb-1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></a>';      
           }
          }
        ]
      });


      $('#griddinein').on('click', 'a.gridDetail', function (e) {
        e.preventDefault();
        const rowData = grid2.row($(this).closest('tr')).data();

        window.location = "{{ url('/order/detail') . '/' }}"+ rowData.id;
      });

      $('#filterColumn').on('change',function(e){
        if($(this).val() == ""){
          $('#filterText').val(null);
          $('#filterText').attr("disabled", "disabled")
        } else {
          $('#filterText').removeAttr("disabled")
        }
      });

      $('#reset').on('click', function(e){
        $('#filterColumn').val("").change();
        $('#periodeLog').val(null);
        fDate.clear();
        $('#filterText').val(null);
        $('#filterText').attr("disabled", "disabled");

        grid2.ajax.reload()
      });

      $("#apply").on("click", function(){
        let dataTes = [{
          action: "asdasdasdasdas",
          createdat: "2021-04-25 03:20:31",
          messages: "Promo berhasil ditambah",
          path: "promo/simpan",
          status: "success",
          username: "superadmin"
        }];
        grid2.ajax.reload()
      });
    });
  </script>
@endsection