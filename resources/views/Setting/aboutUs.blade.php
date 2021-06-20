@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Tentang Kami</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Aplikasi</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Tentang Kami</a></li>
  </ol>
@endsection

@section('content-table')


<div id="privacyWrapper" class="">
                <div class="privacy-container">
                    <div class="privacyContent">

                        <div class="d-flex justify-content-between privacy-head">
                            <div class="privacyHeader">
                                <h1>Tentang Kami</h1>
                                <p>Aplikasi ini di Lisensi oleh Ikhwan Komputer</p>
                            </div>
                        </div>

                        <div class="privacy-content-container">

                            <section>
                                <h5>Siapa Kami?</h5>
                                <p>Ikhwan Komputer, Badan Usaha
                                yang bergerak dibidang Komputer Teknologi, Elektronika, Penjualan dan Jasa Perbaikan Komputer. 
                                Ikhwan Komputer berdiri pada tahun 1998 bulan Mei dan boleh dikatakan sebagai Badan Usaha Pertama 
                                yang bergerak dibidang Teknologi Komputer di Kabupaten Kerinci (Sebelum Berdirinya Kota Sungai Penuh).
                                 Telah banyak memiliki jaringan Kerja Sama Perbaikan dan Perawatan Komputer (Maintenance)
                                  di Dinas Instansi dan Perusahaan Swasta yang ada di Kota Sungai Penuh dan Kabupaten Kerinci. </p>
                            </section>
                            <section>                               
                                <h5>Siap Melayani</h5>
                                <p>1. Perawatan Perangkat Keras (Hardware)</p>
                                <p>2.	Instalasi Program (Software)</p>
                                <p>3. Perbaikan </p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- CPU</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Laptop</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Monitor</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Printer Tinta Dan Dot Matrix (Pita & Jarum)</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- UPS</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- CCTV</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- PABX</p>
                                <p>4. Penjualan Aksesories Komputer</p>
                                <p>5. Instalasi Telepon PABX, Running Text, CCTV, Jaringan Komputer</p>
                                <p>6. Pembuatan</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Aplikasi</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Jadwal Sholat</p>
                            </section>
                            <section>

                                <h5> Hubungi Kami </h5>
                                <p>Telepon</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- 0813-6770-3965</p>
                                <p>Alamat</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Jl.Sisingamangaraja No.23</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Kota Sungai Penuh</p>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;- Jambi</p>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7974.467893558946!2d101.39601!3d-2.062349!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x832d5a6056f4e8a2!2sIkhwan%20Komputer%20Elektronika!5e0!3m2!1sid!2sid!4v1614795924498!5m2!1sid!2sid" class="imgrespo" style="border:0;" allowfullscreen="" loading="lazy"></iframe>  
                                
                            </section>
                                

                        </div>

                    </div>
                </div>
            </div>


            
@endsection

@section('js-table')
  <script>
    $(document).ready(function (){
      let grid = $('#grid').DataTable({
        ajax: {
          url: "{{ url('setting/grid') }}",
          dataSrc: ''
      },
        dom: '<"row"<"col-md-12"<"row"<"col-md-1"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{ 
              text: "Tambah Pelanggan",
              className: 'btn',
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/cust/detail') }}";
              }
            }]
        },
        "processing": false,
        "serverSide": false,
        "oLanguage": {
          "oPaginate": { "sPrevious": '<i data-feather="arrow-left"></i>', "sNext": '<i data-feather="arrow-right"></i>' },
          "sInfo": "Halaman _PAGE_ dari _PAGES_",
          "sSearch": '<i data-feather="search"></i>',
          "sSearchPlaceholder": "Cari...",
          "sLengthMenu": "Hasil :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [10, 20, 50],
        "pageLength": 15,
        columns: [
          { 
            data: 'settingcategory',
            searchText: true
          },
          { 
              data: 'settingkey',
              searchText: true
          },
          { 
              data: 'settingvalue',
              searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              let icon = "";
              
              if(data.can_save)
                icon += '<a href="#" title="Edit" class="gridEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit p-1 br-6 mb-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>';
              
              return icon;
            }
          }
        ]

      });
      $('#grid').on('click', 'a.gridEdit', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();

        window.location = "{{ url('/setting/detail') . '/' }}" + rowData.id;
      });
      $('#grid').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('cust/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Data Pelanggan';
        const pesan = 'Apakah anda yakin ingin menghapus data ini?'
        //console.log(rowData, url)
        gridDeleteRow(url, title, pesan, grid);
      });
    });
  </script>
@endsection
