@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Laporan Transaksi</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Laporan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Laporan Transaksi</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <form id="formsub" class="needs-validation" method="get" novalidate action="{{ url('/laporan/') }}">
      <div class="form-row">     
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
        <div class="col-md-6 mb-1">
          <h4>Periode</h4>
          <input id="start" value="{{request('startdate')}}" name="startdate" class="form-control flatpickr flatpickr-input date" required>
        </div>
        <div class="col-md-6 mb-1">
          <h4>Status Pemasukan</h4>
          <select id='status' class="form-control" name="status">
            <option value="">Semua</option>
            <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : ''}}>Draf</option>
            <option value="DP" {{ request('status') == 'DP' ? 'selected' : ''}}>DP</option>
            <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : ''}}>Lunas</option>
            <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : ''}}>Selesai</option>
            <option value="VOIDED" {{ request('status') == 'VOIDED' ? 'selected' : ''}}>Dibatalkan</option>
          </select>
        </div> 
      </div>
      <div class="float-right mb-3">
        <button class="btn btn-primary mt-2" id="print">Cetak</button>
        <button class="btn btn-primary mt-2" id="sub" type="submit">Cari</button>
      </div>
    </form>
  @if(count($data) > 0)
    <div class="table-responsive mb-4 mt-4">
      <hr>
      <!-- <h3 style="color:#1b55e2">Hasil Pencarian</h3> -->
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Tipe Transaksi</th>
            <th>Kode Transaksi</th>
            <th>Nama Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Tgl. Transaksi</th>
            <th>Debit</th>
            <th>Kredit</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $key=>$row)
          <?php
            $link = $row->trxtype == 'Pemasukan' 
              ? '/order/detail'
              : '/pengeluaran/detail';
          ?>
          <tr>
            <td>{{$key + 1}}</td>
            <td>{{ $row->trxtype }}</td>
            <td><a href="{{ url($link)}}/{{$row->id }}">{{ $row->trxcode }}</a></td>
            <td>{{ $row->trxname }}</td>
            <td>{{ $row->customername }}</td>
            <td>{{ $row->trxdate }}</td>
            <td>{{ $row->debit == null ? '-' : number_format($row->debit) }}</td>
            <td>{{ $row->kredit == null ? '-' : number_format($row->kredit) }}</td>
            <td><b>{{ $row->trxstatus }}</b></td>
          </tr>
          @endforeach
        </tbody>
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
      let fDate = flatpickr($('#start'), {
        mode: "range",
        altinput: true,
        altformat: "Y-m-d",
        dateFormat: "d-m-Y",
        defaultDate: [
          "{{ request('startdate') != null ? request('startdate') : Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}",
          "{{ request('enddate') != null ? request('enddate') : Carbon\Carbon::now()->endOfMonth()->format('d-m-Y')}}"
        ],
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

      $('#formsub').on('submit', function(){
        let us = $( "#user option:selected" ).text();
        $('#domkar').val(us)
      })

      $('#grid').DataTable( {
        dom: '<"row"<"col-md-12" ><"col-md-12"rt> >',
        paging: false
      });
    
    });
  </script>
@endsection
