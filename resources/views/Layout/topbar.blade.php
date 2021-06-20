<div class="header-container">
	<header class="header navbar navbar-expand-sm">
		<a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
		<div class="nav-logo align-self-center">
			<a class="navbar-brand" href="{{url('/')}}"><span class="navbar-brand-name">{{ session('cafeName') }}</span></a>
		</div>
		<ul class="navbar-item topbar-navigation">
			<!-- BEGIN TOPBAR -->
			<div class="topbar-nav header navbar" role="banner">
				<nav id="topbar">
					<ul class="navbar-nav theme-brand flex-row text-center">
						<li class="nav-item theme-logo">
							<a href="index.html">
								<img src="{{ url('/') }}/assets/img/90x90.jpg" class="navbar-logo" alt="logo">
							</a>
						</li>
						<li class="nav-item theme-text">
							<a href="index.html" class="nav-link"> Cape </a>
						</li>
					</ul>
					<ul class="list-unstyled menu-categories" id="topAccordion">
						<li class="menu {{Request::segment(1) == null ? 'active' : ''}}">
							<a href="{{url('/')}}" class="dropdown-toggle">
								<div class="">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home shadow-icons"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									<span>Dashboard</span>
								</div>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
							</a>
						</li>
					@if(Perm::can(['user_lihat']) || Perm::can(['jabatan_lihat']) ||
						Perm::can(['meja_lihat']) ||Perm::can(['menu_lihat']))
							<?php
								$segm = (Request::segment(1) == 'menu' || Request::segment(1) == 'user' || Request::segment(1) == 'jabatan' || Request::segment(1) == 'meja');
							?>
							<li class="menu single-menu {{ $segm ? 'active' : ''}}">
								<a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
									<div class="">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu shadow-icons"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
										<span>Master Data</span>
									</div>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
								</a>
								<ul class="collapse submenu list-unstyled animated fadeInUp" id="app" data-parent="#topAccordion">
									@if(Perm::can(['jabatan_lihat']))
										<li><a href="{{ url('/jabatan') }}">Jabatan</a></li>
									@endif
									@if(Perm::can(['user_lihat']))
										<li><a href="{{ url('/user') }}">User</a></li>
									@endif
									@if(Perm::can(['user_lihat']) || Perm::can(['jabatan_lihat']))
										<li class="menu-title"><hr style="margin:0; border-top: solid 1px lightgrey" /> </li>
									@endif
									@if(Perm::can(['meja_lihat']))
										<li><a href="{{ url('/meja') }}">Meja</a></li>
									@endif
									@if(Perm::can(['menu_lihat']))
										<li><a href="{{ url('/menu') }}">Menu</a></li>
									@endif
									@if(Perm::can(['promo_lihat']))
										<li><a href="{{ url('/promo') }}">Promo</a></li>
									@endif
								</ul>
							</li>
						@endif
						@if(Perm::can(['order_lihat']) || Perm::can(['pengeluaran_lihat']))
							<?php
								$segm = (Request::segment(1) == 'order' || Request::segment(1) == 'pengeluaran');
							?>
							<li class="menu single-menu {{ $segm ? 'active' : ''}}">
								<a href="#transaction" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
									<div class="">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign shadow-icons"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
										<span>Transaksi</span>
									</div>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
								</a>
								<ul class="collapse submenu list-unstyled animated fadeInUp" id="transaction" data-parent="#topAccordion">
									@if(Perm::can(['order_lihat']))
										<li><a href="{{ url('/order/meja/view') }}">Meja Pesanan</a></li>
									@endif
									@if(Perm::can(['order_lihat']))
										<li><a href="{{ url('/order/index') }}">Daftar Pesanan Ditempat</a></li>
									@endif
									@if(Perm::can(['order_pembayaran']))
										<li><a href="{{ url('/order/index-bungkus') }}">Daftar Pesanan bungkus</a></li>
									@endif
									@if(Perm::can(['pengeluaran_lihat']))
										<li><a href="{{ url('/pengeluaran') }}">Pengeluaran</a></li>
									@endif
								</ul>
							</li>
						@endif
						@if(Perm::can(['shift_lihat']))
						<?php
							$segm = Request::segment(1) == 'shift';
						?>
						<li class="menu {{ $segm ? 'active' : ''}}">
							<a href="{{url('/shift')}}" class="dropdown-toggle">
								<div class="">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock shadow-icons"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
									<span>Shift</span>
								</div>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
							</a>
						</li>
						@endif
						@if(Perm::can(['laporan_lihat']) || Perm::can(['log_lihat']))
							<?php
								$segm = (Request::segment(1) == 'laporan' || Request::segment(1) == 'log');
							?>
							<li class="menu single-menu {{ $segm ? 'active' : ''}}">
								<a href="#report" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
									<div class="">

									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text shadow-icons"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
										<span>Laporan</span>
									</div>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
								</a>
								<ul class="collapse submenu list-unstyled animated fadeInUp" id="report" data-parent="#topAccordion">
								@if(Perm::can(['laporan_lihat']))
										<li><a href="{{ url('/laporan-pengeluaran') }}">Laporan Pengeluaran</a></li>
									@endif
									@if(Perm::can(['laporan_lihat']))
										<li><a href="{{ url('/laporan') }}">Laporan Transaksi</a></li>
									@endif
									@if(Perm::can(['laporan_lihat']))
										<li><a href="{{ url('/laporan-shift') }}">Laporan Shift</a></li>
									@endif
									@if(Perm::can(['laporan_lihat']))
										<li><a href="{{ url('/laporan-menu') }}">Laporan Menu</a></li>
									@endif
									@if(Perm::can(['log_lihat']))
										<li><a href="{{ url('/log') }}">Log</a></li>
									@endif
								</ul>
							</li>
						@endif
							<?php
								$segm = (Request::segment(1) == 'setting' || Request::segment(1) == 'backupdb');
							?>
							<li class="menu single-menu {{ $segm ? 'active' : ''}}">
								<a href="#setting" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
									<div class="">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings shadow-icons"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
										<span>Aplikasi</span>
									</div>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
								</a>
								<ul class="collapse submenu list-unstyled animated fadeInUp" id="setting" data-parent="#topAccordion">											
									@if(Perm::can(['pengaturan_backupdb']))
										<li><a href="{{ url('/setting/backupdb') }}" >Backup Database</a></li>
									@endif
									@if(Perm::can(['pengaturan_lihat']))
										<li><a href="{{ url('/setting') }}">Pengaturan</a></li>
									@endif
									@if(Perm::can(['pengaturan_notif']))
										<li><a href="{{ url('/setting/notif') }}">Notifikasi</a></li>
									@endif
									<li><a href="{{ url('/setting/aboutus') }}">Tentang kami</a></li>
									<li><a href="{{ url('/setting/hotkey') }}">Tombol Pintas</a></li>
								</ul>
							</li>
					</ul>
				</nav>
			</div>
		</ul>
		<ul class="navbar-item flex-row ml-auto"></ul>
		<span id="notiferror" class="badge badge-danger d-none">Notif Error</span> &nbsp;&nbsp;
		<a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a>
		<ul class="navbar-item flex-row nav-dropdowns">

			<li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
				<a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<div class="media">
						<div class="media-body align-self-center">
								<h6 style="margin-bottom: .3rem !important;"><span>Halo,</span> {{ session('username') }}</h6>
						</div>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
						<img src="{{ asset('/images/avatar.png') }}" class="img-fluid">
					</div>
				</a>
					<div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="userProfileDropdown">
						<div class="user-profile-section">
						</div>
						<div class="dropdown-item">
							<a href="{{ url('/profile') . '/' . session('userid') }}">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>Profile Anda</span>
							</a>
						</div>
						<div class="dropdown-item">
							<a href="{{ url('logout') }}">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Keluar</span>
							</a>
						</div>
					</div>
			</li>
		</ul>
	</header>
</div>