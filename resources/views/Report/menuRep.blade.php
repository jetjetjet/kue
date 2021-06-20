@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Laporan Menu</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Laporan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Laporan Menu</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <form class="needs-validation" method="get" novalidate action="{{ url('/laporan-menu') }}">
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
      </div>
      <div class="float-right mb-3">
        <button class="btn btn-primary mt-2" id="sub" type="submit">Cari</button>
      </div>
    </form>
  @if($data)
    <div class="table-responsive mb-4 mt-4">
      <hr>
      <h3 style="color:#1b55e2">Hasil Pencarian</h3>
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $key=>$row)
          <tr>
            <td>{{$key+1}}</td>
            <td>{{$row['menuname']}}</td>
            <td>{{number_format($row['menuprice'])}}</td>
            <td>{{$row['totalorder']}}</td>
            <td>{{number_format($row['grantotal'])}}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th>No</th>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
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
                              "<h2 style='color:#1b55e2'>Laporan Menu</h2>"+
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
