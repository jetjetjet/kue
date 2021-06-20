<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Pesanan</title>
    <link rel="icon" type="image/x-icon" href="{{ url('/') }}/assets/img/favicon.ico"/>
    <link href="{{ url('/') }}/assets/css/loader.css" rel="stylesheet" type="text/css" />
    <script src="{{ url('/') }}/assets/js/loader.js"></script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ url('/') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/datatables.css">
    <style>
      .cards tbody tr {
        float: left;
        width: 19rem;
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

      .table .avatar {
        width: 50px;
      }

      .cards .avatar {
        width: 150px;
        margin: 15px;
      }

      ol {
        margin-bottom: 0,
        font-size: 15px
      }
      li{
        font-size: 15px
      }
    </style>
    <!-- END GLOBAL MANDATORY STYLES -->
</head>
<body>
  <div class="header-container">
    <header class="header navbar navbar-expand-sm">
        <div class="nav-logo align-self-center">
          <h3 class="text-center">Daftar Pesanan</h3>
        </div>

        <ul class="navbar-item flex-row ml-auto">
        </ul>

        <span id="notiferror" class="badge badge-danger">Notif Error</span> &nbsp; &nbsp;
        <ul class="navbar-item flex-row nav-dropdowns">
          <a href="{{ url('/')}}" type="button" style="float-right" class="btn btn-info">Kembali Ke Aplikasi</a>
        </ul>
    </header>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="widget-content widget-content-area br-6">
        <div class="table-responsive mb-4 mt-4">
          <table id="example" class="table table-sm table-hover cards" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th></th>
                <th>Jenis Pesanan</th>
                <th>Tanggal</th>
                <th>Pesanan</th>
              </tr>
            </thead>
        </div>
      </div>
    </div>
  </div>
  <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
  <script src="{{ url('/') }}/assets/js/libs/jquery-3.1.1.min.js"></script>
  <script src="{{ url('/') }}/bootstrap/js/popper.min.js"></script>
  <script src="{{ url('/') }}/bootstrap/js/bootstrap.min.js"></script>
  <script src="{{ url('/') }}/plugins/table/datatable/datatables.js"></script>
  <!-- <script src="https://js.pusher.com/7.0/pusher.min.js"></script> -->
  <!-- END GLOBAL MANDATORY SCRIPTS -->
  <script>
    $(document).ready(function (){
      const pMaster = "{{ $ipserver }}";
      let ws = new WebSocket('ws://'+ pMaster +':8910/kapews');
      ws.onmessage = function(e) { 
        table.ajax.reload(); 
      };
      ws.onopen = function(e) {
        $('#notiferror').addClass('d-none')
      }
      
      ws.onerror = function(e) { 
        $('#notiferror').removeClass('d-none')
      }

      let table = $('#example').DataTable({
        'paging':   false,
        'ordering': false,
        "ajax": {
          url: "{{ url('/dapur/lists') }}",
          dataSrc: ''
        },
        'dom':
          // "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'<'float-md-right ml-2'B>f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        'buttons': [, {
          'text': '<i class="fa fa-id-badge fa-fw" aria-hidden="true"></i>',
          'action': function (e, dt, node) {

            $(dt.table().node()).toggleClass('cards');
            //  $('.fa', node).toggleClass(['fa-table', 'fa-id-badge']);

            dt.draw('page');
          },
          'className': 'btn-sm',
          'attr': {
            'title': 'Change views',
          }
        }],
        'select': 'single',
        'columns': [
            {
              'orderable': false,
              'data': null,
              'className': 'text-center',
              'render': function(data, type, full, meta){
                data = "<b>" + data.orderinvoice+ "</b><p>"+data.orderboardtext+"</p>";
                
                return data;
              }
            },
            {
              'data': 'ordertype',
              'class': 'text-right'
            },
            {
              'data': 'orderdate',
              'class': 'text-right'
            },{
              'data': null,
              'render': function(data, type, full, meta){
                let sub = data.subOrder,
                  makanan = "<br>Makanan<ul>",
                  minuman = "</ul>Minuman<ul>";
                sub.forEach(function(e){
                  let temp = "";
                  temp += "<li> " + e.odmenutext + " x<b>" + e.odqty+"</b>";
                  e.odremark != null 
                    ? temp += "<br>" + e.odremark + "</li>"
                    : temp += "</li>"
                  
                  e.odmenutype == 'Makanan'
                    ? makanan += temp
                    : minuman += temp
                })
                  
              return makanan + minuman + "</ul>";
              }
            }
        ],
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
      })
    });
  </script>
</body>
</html>