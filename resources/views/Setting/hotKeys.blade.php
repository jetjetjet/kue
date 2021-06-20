@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Tombol Pintas</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Aplikasi</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Tombol Pintas</a></li>
  </ol>
@endsection

@section('content-table')


<div id="privacyWrapper" class="">
                <div class="privacy-container">
                    <div class="privacyContent">

                        <div class="d-flex justify-content-between privacy-head">
                            <div class="privacyHeader">
                                <h1>Tombol Pintas</h1>
                            </div>
                        </div>

                        <div class="privacy-content-container">

                            <section>
                                <h5>Global (Semua Halaman)</h5>
                                <p><b style="color: #007bff;">Esc</b> Atau <b style="color: #007bff;">*</b> Untuk membuka laci</p>
                                <p><b style="color: #007bff;">P</b> Untuk ping ke printer</p>
                                <p><b style="color: #007bff;">.</b> Ke halaman meja</p>
                            </section>
                            <hr>
                            <section>
                              <h5>Halaman Meja</h5>
                              <p><b style="color: #007bff;">Enter</b> Untuk membuat pesanan bungkus baru</p>
                              <p><b style="color: #007bff;">+</b> dan <b style="color: #007bff;">-</b> Untuk bergeser tabel pesanan bungkus</p>
                            </section>
                            <hr>
                            <section>
                              <h5>Halaman Pembayaran</h5>
                              <p><b style="color: #007bff;">Enter</b> Untuk melanjutkan pembayaran setelah memasukkan total pembayaran</p>
                              <p><b style="color: #007bff;">/</b> Untuk mengaktifkan diskon</p>
                              <p><b style="color: #acb0c3;">--------------------------------------</b></p>
                              <p><i style="color: #acb0c3;">Saat Muncul tampilan Konfirmasi/Printer Tidak Terhubung</i></p>
                              <p><b style="color: #007bff;">Enter</b> Untuk melanjutkan pembayaran di tampilan konfirmasi/printer tidak terhubung</p>
                              <p><b style="color: #007bff;">Backspace</b> Untuk menutup tampilan konfirmasi/printer tidak terhubung</p>
                            </section>
                            <section>
                              <h5>Halaman Pesanan</h5>
                              <p><b style="color: #007bff;">Enter</b> Untuk melanjutkan pesanan setelah memasukkan menu</p>
                              <p><b style="color: #acb0c3;">--------------------------------------</b></p>
                              <p><i style="color: #acb0c3;">Saat Muncul tampilan rincian menu</i></p>
                              <p><b style="color: #007bff;">+</b> dan <b style="color: #007bff;">-</b> Untuk menambah/mengurangi jumlah pesanan</p>
                              <p><b style="color: #007bff;">Enter</b> Untuk memasukan menu ke pesanan</p>
                              <p><b style="color: #007bff;">Backspace</b> Untuk menutup tampilan rincian menu</p>
                            </section>


                        </div>

                    </div>
                </div>
            </div>


            
@endsection

@section('js-table')
@endsection
