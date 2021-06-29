<!DOCTYPE html>
<html>
<head>
	<title>Laporan Transaksi</title>
	
  <link href="{{ url('/') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<style type="text/css">
		table tr td,
		table tr th{
			font-size: 7pt;
      -webkit-box-sizing: content-box;
      box-sizing: content-box;
      padding: 8px 6px;
		}
    
    #header,
    #footer {
      position: fixed;
      left: 0;
      right: 0;
      color: #aaa;
      font-size: 0.9em;
    }
    #header {
      top: 0;
      border-bottom: 0.1pt solid #aaa;
    }
    #footer {
      bottom: 0;
      border-top: 0.1pt solid #aaa;
    }
    .page-number:before {
      content: "Halaman " counter(page);
    }
	</style>
  
  <div id="footer">
    <div class="page-number"></div>
  </div>
	<center>
    <h4 style="color:#1b55e2">{{ $data->label }}</h4>
	</center>

	<table class='table table-bordered'>
		<thead>
      <tr>
        <th>No</th>
        <th>Jenis Transaksi</th>
        <th></th>
        <th>Kode Transaksi</th>
        <th>Nama Pelanggan</th>
        <th>Tgl. Transaksi</th>
        <th>Debit</th>
        <th>Kredit</th>
        <th>Status</th>
      </tr>
		</thead>
		<tbody>
			{{ $i = 1 }}
			@foreach($data->grid as $key=>$row)
      <tr>
        <td>{{ $i++ }}</td>
        <td>{{ $row->trxtype }}</td>
        <td>{{ $row->trxname }}</td>
        <td>{{ $row->trxcode }}</td>
        <td>{{ $row->customername }}</td>
        <td>{{ $row->trxdate }}</td>
        <td class="text-right">{{ $row->debit == null ? '-' : number_format($row->debit) }}</td>
        <td class="text-right">{{ $row->kredit == null ? '-' : number_format($row->kredit) }}</td>
        <td><b>{{ $row->trxstatus }}</b></td>
      </tr>
      @endforeach
		</tbody>
    @if(isset($data->sum))
    <?php $sum = $data->sum ?>
    <tfoot>
      <tr class="text-right">
        <td style="border: 10px solid transparent;" colspan="5"></td>
        <td colspan="2"><strong>Total Debit</strong> </td>
        <td colspan="2"><strong>{{ number_format($sum->total_debit) }}</strong></td>
      </tr>
      <tr class="text-right">
        <td style="border: 10px solid transparent;" colspan="5"></td>
        <td colspan="2"><strong>Total Kredit</strong> </td>
        <td colspan="2"><strong>{{ number_format($sum->total_kredit) }}</strong></td>
      </tr>
      @if($sum->total_debit > 0 && $sum->total_kredit > 1)
        <tr class="text-right">
          <td style="border: 10px solid transparent;" colspan="5"></td>
          <td colspan="2"><strong>Sub Total</strong> </td>
          <td colspan="2"><strong>{{ number_format($sum->sub_total) }}</strong></td>
        </tr>
      @endif
    </tfoot>
    @endif
	</table>
  {{-- <script type="text/php">
    if (isset($pdf)) {
      $text = "Halaman {PAGE_NUM} / {PAGE_COUNT}";
      $size = 10;
      $font = $fontMetrics->getFont("Verdana");
      $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
      $x = ($pdf->get_width() - $width) / 2;
      $y = $pdf->get_height() - 35;
      $pdf->page_text($x, $y, $text, $font, $size);
    }
  </script> --}}
</body>
</html>