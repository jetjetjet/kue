@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Pengeluaran</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Transaksi</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/pengeluaran') }}">Pengeluaran</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Pengeluaran</a></li>
  </ol>
@endsection

@section('content-form')
  <!-- <div class="widget-content widget-content-area br-6">
    <div class="row"> -->
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox widget box box-shadow">
        <div class="widget-header">                                
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Pengeluaran</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{ url('/pengeluaran/simpan') }}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-4 mb-3">
                <label for="numbe">Nama</label>
                <input type="text" name="expensename" value="{{ old('expensename', $data->expensename) }}" class="form-control"  placeholder="Nama" required {{ isset($data->expenseexecutedat) ? 'readonly' : '' }}>
              </div>
              @if(empty($data->expenseexecutedat))
              <div class="col-md-4 mb-3">
                  <label for="floo">Tanggal</label>
                  <input id="date" name="expensedate" value="{{ old('expensedate', $data->expensedate) }}" class="form-control flatpickr flatpickr-input">
              </div>
              @else
              <div class="col-md-4 mb-3">
                  <label for="floo">Tanggal</label>
                  <input id="text" name="expensedate" value="{{ $data->expensedateraw }}" class="form-control" readonly>
              </div>
              @endif
              <div class="col-md-4 mb-3">
                  <label for="floo">Jumlah Pengeluaran</label>
                  <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                  </div>
                  <input type="number" name="expenseprice" value="{{ old('expenseprice', $data->expenseprice) }}" class="form-control text-right" id="pricing" placeholder="Jumlah" required {{ isset($data->expenseexecutedat) ? 'readonly' : '' }}>
                </div>
              </div>
              <div class="col-md-12 mb-3">
                  <label for="floo">Detail Pengeluaran</label>
                  <textarea rows="3" required name="expensedetail" {{ isset($data->expenseexecutedat) ? 'readonly' : '' }} class="form-control" placeholder="Detail">{{ old('expensedetail', $data->expensedetail) }}</textarea>
              </div>
            </div>
            <div class="float-right">
              <a href="{{ url('/pengeluaran') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
              <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
            </div>
          </form>
          @if($data->expenseexecutedby == '0' && Perm::can(['pengeluaran_proses']))
          <form method="post" novalidate action="{{ url('/pengeluaran/proses').'/'.$data->id }}">
            <input type="hidden" name="_token"  value="{{ csrf_token() }}" />
            <input type="hidden" name="id" value="{{ old('id', $data->id) }}" />
            <div class="float-left">
            <button class="btn btn-warning mt-2" id="proceed" type="submit">Proses</button>
            </div>
          </form>
          @endif
        </div>
      </div>
    <!-- </div>
  </div> -->
@endsection

@section('js-form')
<script>
  
  $(document).ready(function (){
    $('[type=number]').setupMask(0);
    flatpickr($('#date'), {
      altinput: true,
      altformat: "Y-m-d",
      dateFormat: "d-m-Y",
      maxDate: "today",
      defaultDate: "{{ $data->expensedate != null ? $data->expensedateraw : 'today' }}"
    });


    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }else if (form.checkValidity() === true){
        $('#sub').attr('disabled', true);
        }
        form.classList.add('was-validated');
      }, false);
    });
  })
</script>
@endsection