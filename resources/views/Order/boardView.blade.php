@extends('Layout.layout-noframe')

@section('css-order')
  <style>
    .dtrg-group {
      width: 100% !important;
      margin: 0.5rem !important;
    }
    .cards tbody tr {
      float: left;
      width: 14rem;
      margin: 0.5rem;
      border: 0.0625rem solid rgba(0, 0, 0, .125);
      border-radius: .25rem;
      box-shadow: 0.25rem 0.25rem 0.5rem rgba(0, 0, 0, 0.25);
    }
    .cards tbody td {
      display: block;
    }
    .cards thead {
      display: none;
    }
    .cards td:before {
      content: attr(data-label);
      position: relative;
      float: left;
      color: #808080;
      min-width: 4rem;
      margin-left: 0;
      margin-right: 1rem;
      text-align: left;   
    }
    tr.selected td:before {
      color: #CCC;
    }
  </style>
@endsection

@section('content-order')
  <div class="widget-content widget-content-area br-6">
  <!-- <div class="alert alert-arrow-left alert-icon-left alert-light-primary mb-4" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
    <strong>Info!</strong> Klik icon berwana Merah untuk membuat pesanan baru dan Biru untuk melihat detail pesanan.
  </div> -->
    @if(Perm::can(['order_lihatBungkus']))
      <div class="table-responsive mb-4 mt-4">
        <h4>Pesanan Bungkus</h4>
        <table id="gridBungkus" class="table table-hover" style="width:100%">
          <thead>
            <tr>
              <th>No. Pesanan</th>
              <th>Tgl.</th>
              <th>Harga</th>
              <th class="no-content"></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    @endif
    <div class="table-responsive mb-4 mt-4">
      <h4>Daftar Meja</h4>
      <table id="grid" class="table table-hover cards" style="width:100%">
        <thead>
          <tr>
            <th></th>
            <th>Status</th>
            <th class="no-content"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('js-order')
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>
    $(document).ready(function (){
      //console.log(ws.Open)
      //console.log(ws.readyState)
      ws.onmessage = function(e) { 
        grid.ajax.reload();
        @if(Perm::can(['order_lihatBungkus']))
          gridBungkus.ajax.reload();
        @endif
      };

      let notif = localStorage.getItem("notif") ?? false;
      setTimeout(() => {
        if(notif){
          ws.send('Ok')
        }
      }, 2000);
      //shortcut
      Mousetrap.bind('enter', function() {
        $('#bgks').trigger('click')
      });
      Mousetrap.bind('plus', function() {
        $('#gridBungkus_next').trigger('click')
      });
      Mousetrap.bind('-', function() {
        $('#gridBungkus_previous').trigger('click')
      });


      @if(Perm::can(['order_lihatBungkus']))
        let gridBungkus = $('#gridBungkus').DataTable({
          ajax: "{{ url('order/grid/bungkus') }}",
          processing: true,
          serverSide: true,
          paging: true,
          ordering: true,
          pageLength: 5,
          dom: 
            '<"row"<"col-md-12"<"row"<"col-md-6"B> > >' +
            '<"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
          buttons: {
            buttons: [{ 
              text: "Tambah Baru",
              className: 'btn',
              attr: {
                id: 'bgks',
              },            
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/order') }}" + "?type=TAKEAWAY";
              }
            }]
          },
          oLanguage: {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Halaman _PAGE_ dari _PAGES_",
            "sSearch": '<i data-feather="search"></i>',
            "sSearchPlaceholder": "Cari...",
            "sLengthMenu": "Hasil :  _MENU_",
          },
          columns: [
            { 
              data: 'orderinvoice',
              name: 'orderinvoice'
            }, { 
              data: 'orderdate',
              name: 'orderdate'
            },{
              data: null,
              name: 'orderprice',
              className: 'text-right',
              render: function(data, type, full, meta){
                return formatter.format(data.orderprice)
              }
            },{
              data: null,
              className: 'text-center',
              render: function(data, type, full, meta){
                let url = "{{url('/order/detail/')}}" + "/" +data.id;
                let url2 = "{{url('/order/')}}" + "/" +data.id;
                return '<a href="' + url2 + '"><span class="badge badge-info">Ubah</span></a> &nbsp <a href="' + url + '"><span class="badge badge-success">Bayar</span></a>';
              }
            }
          ]
        })
      @endif

      let grid = $('#grid').DataTable({
        ajax: {
          url: "{{ url('order/meja/lists') }}",
          dataSrc: ''
        },
        paging: false,
        ordering: false,
        dom: 
          // "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'<'float-md-right ml-2'B>f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-7'p>>",
        buttons: {
          buttons: [{ 
            text: "Tambah Baru",
            className: 'btn',
            action: function ( e, dt, node, config ) {
              
            }
          }]
        },
        'select': 'single',
        columns: [
          { 
            'orderable': false,
            'data': null,
            'className': 'text-center',
            'render': function(data, type, full, meta){
              data = "<h4><b> Meja No." + data.boardnumber+ "</b></h4>";
              return data;
            }
          }, { 
            data: null,
            searchText: false,
            'render': function(data, type, full, meta){
              if(!data.boardstatus){
                return "<b>Terisi</b>";
              }
              return "<b>Kosong</b>";
            }
          },{
            'orderable': false,
            'data': null,
            'className': 'text-center',
            'render': function(data, type, full, meta){
              let url = '#';
              if(!data.boardstatus){
                if(data.is_kasir){
                  url = "{{url('/order/detail')}}"+"/"+ data.orderid;
                } else if(data.is_pelayan){
                  url = "{{url('/order')}}"+"/"+ data.orderid;
                }

                return '<a href="' + url + '"><span class="badge badge-primary">' +  data.orderinvoice + '</span></a>';
              }
              if(data.is_pelayan){url = "{{url('/order')}}" + "?idMeja=" +data.boardid+ "&mejaTeks=Meja No." + data.boardnumber+ " - Lantai "+data.boardfloor;
                return '<a href="' + url + '"><span class="badge badge-danger">Pesanan Baru</span></a>';
              }else{
                return '<span class="badge badge-warning">Meja Kosong</span>';
              }
            }
          }
        ],
        rowGroup: {
          dataSrc: function (row) {
            return "Lantai " + row.boardfloor
          }
        },
        'drawCallback': function (settings) {
          var api = this.api();
          var $table = $(api.table().node());
          
          if ($table.hasClass('cards')) {
            // Create an array of labels containing all table headers
            var labels = [];
            $('thead th', $table).each(function () {
                labels.push($(this).text());
            });

            // Add data-label attribute to each cell
            $('tbody tr', $table).each(function () {
                $(this).find('td').each(function (column) {
                  $(this).attr('data-label', labels[column]);
                });
            });

            var max = 0;
            $('tbody tr', $table).each(function () {
                max = Math.max($(this).height(), max);
            }).height(max);

          } else {
            // Remove data-label attribute from each cell
            $('tbody td', $table).each(function () {
                $(this).removeAttr('data-label');
            });

            $('tbody tr', $table).each(function () {
                $(this).height('auto');
            });
          }
        }
      });
    });
  </script>
@endsection
