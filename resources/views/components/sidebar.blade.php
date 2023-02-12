@php
$route = Route::currentRouteName();
@endphp
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('dashboard') }}"><img src="assets/images/logo/logo.jpeg"
                            style="width:8rem; height:10rem;object-fit:cover;" class="" alt="Logo" srcset=""></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>
                <li class="sidebar-item  ">
                    <a href="{{ route('dashboard') }}" class="sidebar-link  @if ($route ===  'dashboard') text-primary
                        @endif">
                        <i class=" w-5 fas fa-home  @if ($route === 'dashboard') text-primary @endif"></i>
                        <span>Halaman Utama</span>
                    </a>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#"
                        class="sidebar-link @if ($route==='transaction' || $route==='transaction.report' ) text-primary @endif ">
                        <i style="font-size:1.25rem"
                            class=" w-5  fas fa-receipt @if ($route==='transaction' || $route==='transaction.report' ) text-primary @endif"></i>
                        <span>Penjualan</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="{{ route('transaction') }}">Data Penjualan</a>
                        </li>
                        @if (auth()->user()->user_type==='OWNER')
                        <li class="submenu-item ">
                            <a href="{{ route('transaction.report') }}">Laporan Penjualan</a>
                        </li>
                        @endif
                    </ul>
                </li>
                <li class="sidebar-item  has-sub">
                    <a href="#"
                        class="sidebar-link @if ($route==='procurement' || $route==='procurement.report' ) text-primary @endif">
                        <i
                            class=" w-5 fas fa-truck @if ($route==='procurement' || $route==='procurement.report' ) text-primary @endif"></i>
                        <span class="">Pembelian</span>
                    </a>
                    <ul class="submenu ">
                        <li class="submenu-item ">
                            <a href="{{ route('procurement') }}">Data Pembelian</a>
                        </li>
                        @if (auth()->user()->user_type==='OWNER')
                        <li class="submenu-item ">
                            <a href="{{ route('procurement.report') }}">Laporan Pembelian</a>
                        </li>
                        @endif
                    </ul>
                </li>
                <li class="sidebar-item ">
                    <a href="{{ route('product') }}" class="sidebar-link  @if ($route === 'product') text-primary
                        @endif">
                        <i class=" w-5 fas fa-boxes @if ($route === 'product') text-primary @endif"></i>
                        <span>Produk</span>
                    </a>
                </li>
                <li class="sidebar-item  ">
                    <a href="{{ route('customer') }}" class="sidebar-link  @if ($route === 'customer') text-primary
                        @endif">
                        <i class=" w-5 fas fa-users @if ($route === 'customer') text-primary @endif"></i>
                        <span>Pelanggan</span>
                    </a>
                </li>
                <li class="sidebar-item  ">
                    <a href="{{ route('supplier') }}" class="sidebar-link  @if ($route === 'supplier') text-primary
                        @endif">
                        <i class=" w-5 fas fa-warehouse @if ($route === 'supplier') text-primary @endif"></i>
                        <span>Pemasok</span>
                    </a>
                </li>
                @if (auth()->user()->user_type==='OWNER')
                <li class="sidebar-title">Pengaturan</li>
                <li class="sidebar-item  ">
                    <a href="{{ route('user') }}" class="sidebar-link  @if ($route === 'user') text-primary @endif">
                        <i class=" w-5 fas fa-user @if ($route === 'user') text-primary @endif"></i>
                        <span>Pengguna</span>
                    </a>
                </li>
                <li class="sidebar-item  ">
                    <a href="{{ route('company') }}" class="sidebar-link  @if ($route === 'company') text-primary
                        @endif">
                        <i class=" w-5 fas fa-building @if ($route === 'company') text-primary @endif"></i>
                        <span>Perusahaan</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
