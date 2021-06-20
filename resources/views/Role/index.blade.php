@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Jabatan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Jabatan</a></li>
  </ol>
@endsection

@section('content-table')
  <div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>Nama Jabatan</th>
            <th>Detail</th>
            <th class="no-content"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
            <th>Nama Jabatan</th>
            <th>Detail</th>
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
      let grid = $('#grid').DataTable({
        ajax: {
          url: "{{ url('jabatan/grid') }}",
          dataSrc: ''
        },
        dom: '<"row"' +
            @if(Perm::can(['jabatan_simpan']))
            '<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > >' +
            @endif
            '<"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
          buttons: [{ 
            text: "Tambah Baru",
            className: 'btn',
            action: function ( e, dt, node, config ) {
              window.location = "{{ url('/jabatan/detail') }}";
            }
          }]
        },
        "processing": false,
        "serverSide": false,
        "oLanguage": {
          "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
          "sInfo": "Halaman _PAGE_ dari _PAGES_",
          "sSearch": '<i data-feather="search"></i>',
          "sSearchPlaceholder": "Cari...",
          "sLengthMenu": "Hasil :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [10, 20, 50],
        "pageLength": 10,
        columns: [
          { 
            data: 'rolename',
            searchText: true
          },
          { 
            data: 'roledetail',
            searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              let icon = "";
              
              if(data.can_save)
                icon += '<a href="#" title="Edit" class="gridEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit p-1 br-6 mb-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>';
              
              if(data.can_delete)
                icon += '<a href="#" title="Delete" class="gridDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>';
              return icon;
            }
          }
        ]
      });
      
      $('#grid').on('click', 'a.gridEdit', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();
        window.location = "{{ url('/jabatan/detail') . '/' }}"+ rowData.id;
      });

      $('#grid').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('/jabatan/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Jabatan';
        const pesan = 'Apakah anda yakin ingin menghapus jabatan ini?'
        gridDeleteRow(url, title, pesan, grid);
      });
    });
  </script>
@endsection
