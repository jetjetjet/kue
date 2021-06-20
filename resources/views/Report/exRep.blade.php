@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Laporan Pengeluaran</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Laporan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Laporan Pengeluaran</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <form class="needs-validation" method="get" novalidate action="{{ url('/laporan-pengeluaran') }}">
      <div class="form-row">     
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
        <div class="col-md-6 mb-1">
          <h4>Tanggal Awal</h4>
          <input id="start" value="{{request('startdate')}}" name="startdate" class="form-control flatpickr flatpickr-input date">
        </div>
        <div class="col-md-6 mb-1">
          <h4>Tanggal Akhir</h4>
          <input id="end" value="{{request('enddate')}}" name="enddate" class="form-control flatpickr flatpickr-input date">
        </div>
        <div class="col-md-12 mb-1">
          <h4>Status</h4>
          <select id='status' class="form-control" name="status">
            <option value="Semua">Semua</option>
              <option value="1" {{ request('status') == '1' ? 'selected' : ''}}>Dilaksanakan</option>
              <option value="0" {{ request('status') == '0' ? 'selected' : ''}}>Draft</option>
          </select>
        </div> 
      </div>
      <div class="float-right mb-3">
        <button class="btn btn-primary mt-2" id="sub" type="submit">Cari</button>
      </div>
    </form>
  @if($data->sub['total'] != 0)
    <div class="table-responsive mb-4 mt-4">
      <hr>
      <h3 style="color:#1b55e2">Hasil Pencarian</h3>
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Tanggal</th>
            <th>Jumlah</th>
            <th>Karyawan</th>
            <th>Status</th>
            <th>Diselesaikan oleh</th>
            <th>Tanggal Diselesaikan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $key=>$row)
          <tr>
            <td>{{$key + 1}}</td>
            <td><a href="{{url('/pengeluaran/detail')}}/{{$row['id']}}">{{$row['expensename']}}</a></td>
            <td>{{$row['tanggal']}}</td>
            <td>{{number_format($row['expenseprice'])}}</td>
            <td>{{$row['create']}}</td>
            <td>{{$row['status']}}</td>
            <td>{{$row['execute']}}</td>
            <td>{{$row['tanggalend']}}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="text-right"><h3>Total : </h3></th>
            <th><h3><b>{{number_format($data->sub['total'])}}</b></h3></th>
          </tr>
        </tfoot>
      </table>
      </div>      
    </div>
    @else
    <div class="table-responsive mb-4 mt-4">
    <div style="text-align:center;">
      <h3>Data Kosong</h3>
    </div>
  </div>
    @endif
  </div>
@endsection

@section('js-form')
  <script>


    $(document).ready(function (){

      $('#user').select2({
      tags: false,
      searchInputPlaceholder: 'Search options',
      });
      
      flatpickr($('#start'), {
        dateFormat: "d-m-Y",
        altinput: true,
        altformat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('startdate') != null ? request('startdate') : Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}",
        onChange: function (selectedDates, dateStr, instance) {
          endPicker.set("minDate", dateStr);
          $('#end').removeAttr('disabled')
        }
      });

      let endPicker = flatpickr($('#end'), {
        dateFormat: "d-m-Y",
        altinput: true,
        altformat: "Y-m-d",
        minDate: "{{request('startdate') != null ? request('startdate') : Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}",
        maxDate: "{{Carbon\Carbon::now()->endOfMonth()->format('d-m-Y')}}",
        defaultDate: "{{ request('enddate') != null ? request('enddate') : Carbon\Carbon::now()->endOfMonth()->format('d-m-Y')}}"
      });
// $('#start').change(function(){
// chg()
// })
// $('#end').change(function(){
// chg()
// })

$('#grid').DataTable( {
            dom: '<"row"<"col-md-12"<"row"<"col-md-6"B> > ><"col-md-12"rt> >',
            buttons: {
                buttons: [
                    { extend: 'copy', text:'Salin', className: 'btn', footer:'true' },
                    { 
                      extend: 'print', 
                      className: 'btn', 
                      title:"",
                      text:'PDF/Cetak',
                      footer:'true',
                      customize: function ( win ) {
                        $(win.document.body)
                          .prepend(
                              "<br><h2><b>{{session('cafeName')}}</b></h2><hr>"+
                              "<h2 style='color:#1b55e2'>Laporan Pengeluaran</h2>"+
                              "<div class='form-row'>"+
                              "<div class='col-md-12'><h4 class='text-right'>{{request('startdate')}}/{{request('enddate')}}</h4></div>"+
                              "</div>"
                          );
                      }
                    },
                ]
            },
            paging: false
        } );
    
    });
  </script>
@endsection
