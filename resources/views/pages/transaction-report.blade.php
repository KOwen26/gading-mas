@extends('layout.master')
@section('title', 'Laporan Penjualan')
@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Laporan Penjualan</h3>
                <br>
                {{-- <p class="text-subtitle text-muted">Navbar will appear in top of the page.</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Halaman Utama</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transaction') }}">Penjualan</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Laporan Penjualan
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Laporan Penjualan</h4>
                    {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#transaction-detail"><i class="fas fa-plus"></i> <span class="ml-2">Tambah
                            Penjualan</span> </button> --}}
                </div>
            </div>
            <div class="card-body">
                <x-transactions.transaction-report-data />
            </div>
        </div>
    </section>
</div>
@endsection
@section('script')

@endsection
