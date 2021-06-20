@extends('Layout.index')

@section('css-body')
	<link href="{{ url('/') }}/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
	<link href="{{ url('/') }}/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />

	<link href="{{ url('/') }}/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
	<link href="{{ url('/') }}/assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
    /*
 * Off Canvas at medium breakpoint
 * --------------------------------------------------
 */

    @media screen and (max-width: 48em) {
      .row-offcanvas {
        position: relative;
        -webkit-transition: all 0.25s ease-out;
        -moz-transition: all 0.25s ease-out;
        transition: all 0.25s ease-out;
      }
      .row-offcanvas-left .sidebar-offcanvas {
        left: -33%;
      }
      .row-offcanvas-left.active {
        left: 33%;
        margin-left: -6px;
      }
      .sidebar-offcanvas {
        position: absolute;
        top: 0;
        width: 33%;
        height: 100%;
      }
    }
    /*
 * Off Canvas wider at sm breakpoint
 * --------------------------------------------------
 */

    @media screen and (max-width: 34em) {
      .row-offcanvas-left .sidebar-offcanvas {
        left: -45%;
      }
      .row-offcanvas-left.active {
        left: 45%;
        margin-left: -6px;
      }
      .sidebar-offcanvas {
        width: 45%;
      }
    }

    .card {
      overflow: hidden;
    }

    .card-block .rotate {
      z-index: 8;
      float: right;
      height: 100%;
    }

    .card-block .rotate i {
      color: rgba(20, 20, 20, 0.15);
      position: absolute;
      left: 0;
      left: auto;
      right: -10px;
      bottom: 0;
      display: block;
      -webkit-transform: rotate(-44deg);
      -moz-transform: rotate(-44deg);
      -o-transform: rotate(-44deg);
      -ms-transform: rotate(-44deg);
      transform: rotate(-44deg);
    }
  </style>
@endsection

@section('content-breadcumb')
	<!-- <div class="page-title">
		<h3>Dashboard</h3>
	</div> -->
@endsection

@section('content-body')
  <div class="row layout-top-spacing">
		<!-- <div class="form-row"> -->
		<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 layout-spacing">
			<a href="{{url('/order/meja/view')}}">
				<div class="widget widget-card-four">
					<div class="widget-content">
						<div class="w-content">
							<div class="w-info">
								<h6 class="value">Meja Pesanan</h6>
							</div>
							<div class="">
								<div class="w-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
								</div>
							</div>
						</div>
						<div class="progress">
							<div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 layout-spacing">
			<a href="{{url('/order')}}">
				<div class="widget widget-card-four">
					<div class="widget-content">
						<div class="w-content">
							<div class="w-info">
								<h6 class="value">Buat Baru</h6>
								<p class="">Transaksi</p>
							</div>
							<div class="">
								<div class="w-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
								</div>
							</div>
						</div>
						<div class="progress">
							<div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 layout-spacing">
			<a href="{{url('/dapur')}}">
				<div class="widget widget-card-four">
					<div class="widget-content">
						<div class="w-content">
							<div class="w-info">
								<h6 class="value">Dapur</h6>
								<p class="">Daftar Pesanan</p>
							</div>
							<div class="">
								<div class="w-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
								</div>
							</div>
						</div>
						<div class="progress">
							<div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<!-- shift -->
		<div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 layout-spacing">
			<a href="{{url('order/index')}}">
				<div class="widget widget-card-four">
					<div class="widget-content">
						<div class="w-content">
							<div class="w-info">
								<h6 class="value">Pesanan</h6>
								<p class="">Tabel</p>
							</div>
							<div class="">
								<div class="w-icon">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-list"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
								</div>
							</div>
						</div>
						<div class="progress">
							<div class="progress-bar bg-gradient-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
			</a>
		</div>
		<!-- </div> -->
		@if(Perm::can(['laporan_lihat']))
		<hr />
			<div class="col-12">    
				<div class="panel">
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for=""> Bulan </label>
									<select id="bln" class="form-control filter">
										@foreach($data->bln as $bln)
											<option value="{{$bln->val}}" {{$bln->skrg ? 'Selected' : ''}}>{{$bln->bln}}</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<label for=""> Tahun </label>
									<select id="thn" class="form-control filter">
										@foreach($data->thn as $t)
											<option value="{{$t}}">{{$t}}</option>
										@endforeach
									</select>
								</div>
								</div>
							</div>
					</div>
				</div>
			</div>
			<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 layout-spacing">
				<div class="widget widget-chart-one">
						<div class="widget-heading">
							<h5 class="">Chart Bulan <span class="blnText">a</span></h5>
							<ul class="tabs tab-pills">
									<li><a href="javascript:void(0);" id="tb_1" class="tabmenu">Bulanan</a></li>
							</ul>
						</div>
						<div class="widget-content">
							<div class="tabs tab-content">
								<div id="content_1" class="tabcontent"> 
									<div id="orderBulanan"></div>
								</div>
							</div>
						</div>
				</div>
			</div>
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 layout-spacing">
				<div class="widget widget-table-three">
					<div class="widget-heading">
						<h5 class="">Penjualan Terbanyak Bulan <span class="blnText"></span></h5>
					</div>
					<div class="widget-content">
						<div class="table-responsive">
							<table class="table topMenu">
								<thead>
									<tr>
										<th><div class="th-content">Menu</div></th>
										<th><div class="th-content th-heading">Harga</div></th>
										<th><div class="th-content th-heading">Total</div></th>
									</tr>
								</thead>
									<tbody>
										<tr class="menuItem"></tr>
									</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection


@section('js-body')
	<script src="{{ url('/') }}/plugins/apex/apexcharts.min.js"></script>
	<script src="{{ url('/') }}/assets/js/dashboard/dash_2.js"></script>
	<script>
		@if(Perm::can(['laporan_lihat']))
			
		@endif
		
		$(document).ready(function (){
			let adminData = getData();
    	adminDashboard(adminData);

			$('.filter').on('change', function(){
				var data = {
					bulan: $('#bln').val(),
					tahun: $('#thn').val()
				};
				var get = getData(data);
				adminDashboard(get);
			})
		})
		
		function getData(val){
			let data = $.ajax({
				url: "{{ url('/dash') }}",
				type: "GET",
				data: val,
				async: false, 
				success: function (data) {
						return data;
				}
			}).responseText;

			return JSON.parse(data);
		}

		function adminDashboard(data){
			$('.blnText').text(data.blnAktif);

			//Chart
			let options = {
				chart: {
					height: 350,
					type: 'area',
					toolbar: {
						show: false,
					}
				},
				dataLabels: {
						enabled: false
				},
				stroke: {
					curve: 'smooth'
				},
				series: [{
					name: 'Pendapatan',
					data: data.chart.chartIncome.split(',')
				}, {
					name: 'Pengeluaran',
					data: data.chart.chartExpense.split(',')
				}],
				xaxis: {
					categories: data.chart.chartTgl.split(','),                
				},
				yaxis: {
					title: {
						text: 'Rupiah'
					}
				},
				fill: {
					opacity: 1
				},
				tooltip: {
					y: {
						formatter: function (val) {
							return "Rp " + val 
						}
					}
				},
				noData: {
					text: 'Loading...'
				}
			}
			document.getElementById('orderBulanan').innerHTML = '';
			let chart = new ApexCharts(
				document.querySelector("#orderBulanan"),
				options
			);
			chart.render();
			
			// Top Menu
			let tbody = $('.topMenu').find('tbody');
			tbody.find('.menuItem').remove();

			data.topMenu.map((val, index) => {
				let tr = '<tr class="menuItem"><td><div class="td-content product-name">'+ val.menuname+'</div></td>'+
					'<td><div class="td-content"><span class="pricing">'+ formatter.format(val.menuprice) +'</span></div></td>'+
					'<td><div class="td-content">'+ formatter.format(val.totalorder) +'</div></td></tr>';
				tbody.append(tr);
			})
		}
	</script>
@endsection

