@extends('Layout.index')

@section('css-body')
	<link href="{{ url('/') }}/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
	<link href="{{ url('/') }}/assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css" />

	<link href="{{ url('/') }}/assets/css/components/custom-media_object.css" rel="stylesheet" type="text/css" />
	<link href="{{ url('/') }}/assets/css/elements/infobox.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/css/dash.css">
@endsection

@section('content-breadcumb')
	<!-- <div class="page-title">
		<h3>Dashboard</h3>
	</div> -->
@endsection

@section('content-body')
	<section class="hero is-info welcome is-small">
		<div class="hero-body">
			<div class="container">
				<h1 class="title">
					Halo, {{ session('username') }}.
				</h1>
				<!-- <h2 class="subtitle">
					I hope you are having a great day!
				</h2> -->
				<a class="btn btn-success" href="{{ url('/') }}/order" onclick="myFunction()">{{ trans('fields.order') }}</a>
			</div>
		</div>
	</section>
	<section class="info-tiles mt-5">
		<div class="tile is-ancestor has-text-centered">
			<div class="tile is-parent">
				<article class="tile is-child box">
					<p class="title">{{ $data->count['orderCount'] }}</p>
					<p class="subtitle">{{ trans('fields.totalTransaction') }}</p>
				</article>
			</div>
			<div class="tile is-parent">
				<article class="tile is-child box">
					<p class="title">{{ $data->count['expenseCount'] }}</p>
					<p class="subtitle">Total Pengeluaran</p>
				</article>
			</div>
			<div class="tile is-parent">
				<article class="tile is-child box">
					<p class="title">{{ $data->count['preOrderSum'] }}</p>
					<p class="subtitle">{{ trans('fields.preOrder') }}</p>
				</article>
			</div>
			<div class="tile is-parent">
				<article class="tile is-child box">
					<p class="title">{{ $data->count['stockSum'] }}</p>
					<p class="subtitle">{{ trans('fields.readyStock') }}</p>
				</article>
			</div>
		</div>
	</section>
  <div class="row layout-top-spacing">
		<div class="col-md-12 col-sm-12 col-12 layout-spacing">
			<a href="{{url('order/index')}}">
				<div class="widget widget-card-four">
					<div class="widget-content">
						<div class="card-body">
							<div class="d-md-flex align-items-center">
								<div>
									<h4 class="card-title">Top Selling Products</h4>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table v-middle">
								<thead>
										<tr class="bg-light">
											<th class="border-top-0">{{ trans('fields.customerName') }}</th>
											<th class="border-top-0">{{ trans('fields.productName') }}</th>
											<th class="border-top-0">{{ trans('fields.estDate') }}</th>
											<th class="border-top-0">{{ trans('fields.qty') }}</th>
											<th class="border-top-0">{{ trans('fields.remark') }}</th>
										</tr>
								</thead>
								<tbody>
										<!-- <td>
											<div class="d-flex align-items-center">
													<div class="m-r-10"><a class="btn btn-circle btn-info text-white">EA</a></div>
													<div class="">
															<h4 class="m-b-0 font-16">Elite Admin</h4>
													</div>
											</div>
										</td> -->
									@foreach($data->PO as $itemPO)
										<tr>
											<td>
												<h4 class="m-b-0 font-16">{{ $itemPO->ordercustname }}</h4>
											</td>
											<td>
												<label class="label label-danger">{{ $itemPO->productname }}</label>
											</td>
											<td>
												<label class="label label-danger">{{ $itemPO->estdate }}</label>
											</td>
											<td>
												<label class="label label-danger">{{ $itemPO->odqty }}</label>
											</td>
											<td> {{ $itemPO->odremark }} </td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						<div class="text-right">
							<a class="btn btn-info" href="#" onclick="myFunction()">{{ trans('fields.readMore') }}</a>
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

