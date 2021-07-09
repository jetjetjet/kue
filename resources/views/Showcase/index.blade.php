@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>{{ trans('fields.showcase') }}</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">{{ trans('fields.masterData') }}</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ trans('fields.showcase') }}</a></li>
  </ol>
@endsection

@section('content-table')
  <div class="widget-content widget-content-area br-6">
    <div class="mb-4">
      <h3>{{ trans('fields.showcase') }} 30 Hari Terakhir</h3>
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
            <option value="productname">{{ trans('fields.productName') }}</option>
            <option value="showcasecode">{{ trans('fields.productionCode') }}</option>
            <!-- <option value="orderstatus">Meja</option> -->
          </select>
        </div>
        <div class="form-group col-1 pl-0">
          <select id="filterStatus" class="form-control form-control-sm" >
            <option value="">{{ trans('fields.all') }} {{ trans('fields.status') }}</option>
            <option value="ReadyStock">{{ trans('fields.readyStock') }}</option>
            <option value="Kadaluarsa">{{ trans('fields.expired') }}</option>
            <option value="Habis">{{ trans('fields.empty') }}</option>
            <!-- <option value="orderstatus">Meja</option> -->
          </select>
        </div>
        <div class="form-group col-2">
          <button class="btn btn-danger" id="reset">Reset</button>
          <button class="btn btn-success" id="apply">Terapkan</button>
        </div>
      </div>
    </fieldset>
    <div class="table-responsive mb-4 mt-4">
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>{{ trans('fields.name')}} {{trans('fields.product')}}</th>
            <th>{{ trans('fields.stock') }}</th>
            <th>{{ trans('fields.status') }}</th>
            <th>{{ trans('fields.date') }}</th>
            <th>{{ trans('fields.expDate') }}</th>
            <th class="no-content"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
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

      let grid = $('#grid').DataTable({
        ajax: {
          url: "{{ url('showcase/grid') }}",
          "data": function(dt){
            return $.extend( {}, dt, {
              "filterDate" : $('#periodeLog').val(),
              'filterText': $('#filterText').val(), 
              'filterColumn': $('#filterColumn').val(), 
              'filterStatus': $('#filterStatus').val()
            } );
          },
        },
        dom: '<"row"' +
          @if(Perm::can(['showcase_simpan']))
          '<"col-md-12"<"row"<"col-md-6"B> > >' +
          @endif
          '<"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{ 
              text: "{{trans('fields.add') }} {{ trans('fields.showcase') }}",
              className: 'btn',
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/showcase/detail') }}";
              }
            }]
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
        "pageLength": 15,
        columns: [
          { 
            data: null,
            searchText: true,
            render: function(data, type, full, meta){
              return data.productname + ' - Kd. Produksi: ' + data.showcasecode;
            }
          },
          { 
            data: null,
            searchText: true,
            render: function(data, type, full, meta){
              if(data.showcaseqty){
                return data.showcaseqty;
              } else{
                return "-";
              }
            }
          },
          { 
            data: null,
            searchText: true,
            render: function(data, type, full, meta){
              if(data.status=="ReadyStock")
                return "<span class='badge badge-success'>ReadyStock</span>"
              if(data.status=="Kadaluarsa")
                return "<span class='badge badge-danger'>Kadaluarsa</span>"
              if(data.status=="Habis")
                return "<span class='badge badge-warning'>Habis</span>"
            }
          },
          { 
            data: 'showcasedate',
            searchText: true
          },
          { 
            data: 'showcaseexpdate',
            searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              let icon = "";
              if(data.can_delete && data.status=='ReadyStock')
                icon += '<a href="#" title="Delete" class="gridDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>';
              if(data.can_save)
                icon += '<a href="#" title="Edit" class="gridEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit p-1 br-6 mb-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>';
              return icon;
            }
          }
        ]
    });

    $('#reset').on('click', function(e){
      $('#filterColumn').val("").change();
      $('#filterStatus').val("").change();
      $('#periodeLog').val(null);
      fDate.clear();
      $('#filterText').val(null);
      $('#filterText').attr("disabled", "disabled");

      grid.ajax.reload()
    });

    $("#apply").on("click", function(){
      grid.ajax.reload()
    });

    $('#grid').on('click', 'a.gridEdit', function (e) {
      e.preventDefault();
      const rowData = grid.row($(this).closest('tr')).data();

      window.location = "{{ url('/showcase/detail') . '/' }}" + rowData.id;
    });

    $('#filterColumn').on('change',function(e){
        if($(this).val() == ""){
          $('#filterText').val(null);
          $('#filterText').attr("disabled", "disabled")
        } else {
          $('#filterText').removeAttr("disabled")
        }
      });
    
    $('#grid').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('showcase/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Menu';
        const pesan = 'Apakah anda yakin ingin menghapus data ini?'
        const batal = 'Data Showcase batal dihapus'
        //console.log(rowData, url)
        gridDeleteRow(url, title, pesan, batal, grid);
      });
    });

  </script>
@endsection
