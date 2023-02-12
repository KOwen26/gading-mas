@extends('layout.master')
@section('title', 'Halaman Utama')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Halaman Utama</h3>
                <br />
                {{-- <p class="text-subtitle text-muted">Navbar will appear in top of the page.</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Halaman Utama
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted font-semibold fs-5">Penjualan Bulan Ini</p>
                                        <h5 class="mb-0">{{ $transaction_report?->sum('transaction_quantity') }}
                                            Transaksi</h5>
                                    </div>
                                    <div style="min-width:40px; min-height:40px"
                                        class="d-grid align-items-center justify-content-center bg-info rounded">
                                        <div class="">
                                            <i class="fa-lg text-white fas fa-receipt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted font-semibold fs-5">Pendapatan Bulan Ini</p>
                                        <h5 class="text-success mb-0">Rp {{ number_format(
                                            $transaction_report?->sum('transaction_grand_total') , '0', '0', '.') }}
                                        </h5>
                                    </div>
                                    <div style="min-width:40px; min-height:40px"
                                        class="d-grid align-items-center justify-content-center bg-success rounded">
                                        <div class="">
                                            <i class="fa-lg text-white fas fa-dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted font-semibold fs-5">Pembelian Bulan Ini</p>
                                        <h5 class="mb-0">{{ $procurement_report?->sum('procurement_quantity') }}
                                            Transaksi</h5>
                                    </div>
                                    <div style="min-width:40px; min-height:40px"
                                        class="d-grid align-items-center justify-content-center bg-info rounded">
                                        <div class="">
                                            <i class="fa-md text-white fas fa-truck"></i>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="text-muted font-semibold fs-5">Pengeluaran Bulan Ini</p>
                                        <h5 class="text-danger mb-0">Rp {{
                                            number_format($procurement_report?->sum('procurement_grand_total'), '0',
                                            '0', '.')
                                            }}</h5>
                                    </div>
                                    <div style="min-width:40px; min-height:40px"
                                        class="d-grid align-items-center justify-content-center bg-danger rounded">
                                        <div class="">
                                            <i class="fa-md text-white fas fa-coins"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted font-semibold fs-5">Akses Cepat</p>
                        <div class="d-grid gap-3">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#transaction-detail"
                                class="btn btn-primary">
                                {{-- <i class="fas fa-plus"></i> --}}
                                Tambah Penjualan</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#procurement-detail"
                                class="btn btn-secondary">
                                {{-- <i class="fas fa-plus"></i> --}}
                                Tambah Pembelian</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#product-detail"
                                class="btn btn-success">
                                {{-- <i class="fas fa-plus"></i> --}}
                                Tambah Produk</button>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#customer-detail"
                                class="btn btn-info">
                                {{-- <i class="fas fa-plus"></i> --}}
                                Tambah Pelanggan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-products.product-modal></x-products.product-modal>
        <x-customers.customer-modal></x-customers.customer-modal>
        <x-transactions.transaction-modal></x-transactions.transaction-modal>
        <x-procurements.procurement-modal></x-procurements.procurement-modal>
    </section>
</div>
@endsection
@section('script')

@endsection
