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
    <div>
    <form id="formsub" class="needs-validation" method="get" novalidate action="{{ url('/laporan/') }}">
      <div class="form-row">
        <div class="col-md-6 mb-1">
          <h4>Periode</h4>
          <input id="start" value="{{request('startdate')}}" name="startdate" class="form-control flatpickr flatpickr-input date" required>
        </div>
        <div class="col-md-6 mb-1">
          <h4>Status Pemasukan</h4>
          <select id='status' class="form-control" name="status">
            <option value="">Selesai</option>
            <option value="DRAFT" {{ request('status') == 'DRAFT' ? 'selected' : ''}}>Draf</option>
            <option value="DP" {{ request('status') == 'DP' ? 'selected' : ''}}>DP</option>
            <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : ''}}>Lunas</option>
            {{-- <option value="COMPLETED" {{ request('status') == 'COMPLETED' ? 'selected' : ''}}>Selesai</option> --}}
            <option value="VOIDED" {{ request('status') == 'VOIDED' ? 'selected' : ''}}>Dibatalkan</option>
          </select>
        </div>
        <div class="col-md-6 mb-1">
          <h4>Karyawan</h4>
          <select id='userid' class="form-control" name="userid">
            <option value = "">Semua</option>
            @foreach($user as $u)
              <option value="{{$u->id}}" {{ request('user') == $u->id ? 'selected' : ''}}>{{$u->username}}</option>
            @endforeach
          </select>
        </div> 
        <div class="col-md-6 mb-1">
          <h4>Tampilkan Pengeluaran</h4>
          <select id='status' class="form-control" name="expense">
            <option value="1">Ya</option>
            <option value="0" {{ request('expense') == '0' ? 'selected' : ''}}>Tidak</option>
          </select>
        </div> 
      </div>
      <div class="float-right mb-3">
        <a class="btn btn-success mt-2" id="print">Cetak</a>
        <a class="btn btn-primary mt-2" id="search">Cari</a>
      </div>
    </form>
  </div>
  @if(isset($data->grid))
    <div class="table-responsive mb-4 mt-4">
      <hr>
      <h4 style="color:#1b55e2">{{ $data->label }}</h4>
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Jenis Transaksi</th>
            <th>#</th>
            <th>Kode Transaksi</th>
            <th>Nama Pelanggan</th>
            <th>Tgl. Transaksi</th>
            <th>Debit</th>
            <th>Discount</th>
            <th>Kredit</th>
            <th>Status</th>
            <th>Oleh</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data->grid as $key=>$row)
          <?php
            $link = $row->trxtype == 'Pemasukan' 
              ? '/order/detail'
              : '/pengeluaran/detail';
          ?>
          <tr>
            <td>{{$key + 1}}</td>
            <td>{{ $row->trxtype }}</td>
            <td>{{ $row->trxname }}</td>
            <td><a href="{{ url($link)}}/{{$row->id }}">{{ $row->trxcode }}</a></td>
            <td>{{ $row->customername }}</td>
            <td>{{ $row->trxdate }}</td>
            <td class="text-right">{{ $row->debit == null ? '-' : number_format($row->debit) }}</td>
            <td class="text-right">{{ $row->discount == null ? '-' : number_format($row->discount) }}</td>
            <td class="text-right">{{ $row->kredit == null ? '-' : number_format($row->kredit) }}</td>
            <td><b>{{ $row->trxstatus }}</b></td>
            <td><b>{{ $row->trxusername }}</b></td>
          </tr>
          @endforeach
        </tbody>
        @if(isset($data->sum))
        <?php $sum = $data->sum ?>
        <tfoot>
          <tr class="text-right">
            <td style="border: 10px solid transparent;" colspan="7"></td>
            <td><h4> <strong>Total Debit</strong> </h4></td>
            <td colspan="3"><h4><strong>{{ number_format($sum->total_debit) }}</strong></h4></td>
          </tr>
          @if($sum->total_discount > 0)
            <tr class="text-right">
              <td style="border: 10px solid transparent;" colspan="7"></td>
              <td><h4> <strong>Total Diskon</strong> </h4></td>
              <td colspan="3"><h4><strong>{{ number_format($sum->total_discount) }}</strong></h4></td>
            </tr>
          @endif
          <tr class="text-right">
            <td style="border: 10px solid transparent;" colspan="7"></td>
            <td><h4> <strong>Total Kredit</strong> </h4></td>
            <td colspan="3"><h4><strong>{{ number_format($sum->total_kredit) }}</strong></h4></td>
          </tr>
          @if($sum->total_debit > 0 && $sum->total_kredit > 1)
            <tr class="text-right">
              <td style="border: 10px solid transparent;" colspan="7"></td>
              <td><h4> <strong>Selisih</strong> </h4></td>
              <td colspan="3"><h4><strong>{{ number_format($sum->sub_total) }}</strong></h4></td>
            </tr>
          @endif
          @if($sum->total_debit > 0 && $sum->total_kredit < 1)
            <tr class="text-right">
              <td style="border: 10px solid transparent;" colspan="7"></td>
              <td><h4> <strong>Selisih</strong> </h4></td>
              <td colspan="3"><h4><strong>{{ number_format($sum->total_debit - $sum->total_discount) }}</strong></h4></td>
            </tr>
          @endif
        </tfoot>
        @endif
      </table>
      </div>      
    </div>
    @else
    <div class="table-responsive mb-4 mt-4">
    </div>
    @endif
  </div>
@endsection

@section('js-form')
  <script>
    $(document).ready(function (){
      $('#print').click(function (e){
        $('[name=print]').remove();
        $('form')
          .append('<input type="hidden" name="print" value="1" />')
          .attr('target', '_blank')
          .submit();
      });

      $('#search').click(function (e){
        $('[name=print]').remove();
        $('form').removeAttr('target').submit();
      });
      
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

      $('#grid').DataTable( {
        dom: '<"row"<"col-md-12" ><"col-md-12"rt> >',
        paging: false
      });
    
    });
  </script>
@endsection
