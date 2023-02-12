@php
$statuses = [['NEW', 'Baru', 'info'], ['FINISHED', 'Selesai', 'success']];
@endphp
@extends('layout.master')
@section('title', 'Pembelian')
@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Pembelian</h3>
                    <br>
                    {{-- <p class="text-subtitle text-muted">Navbar will appear in top of the page.</p> --}}
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Halaman Utama</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pembelian</li>
                            <li class="breadcrumb-item"><a href="{{ route('procurement.report') }}">Laporan Pembelian</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="row">
                @foreach ($statuses as $status)
                    <div class="col-md-3">
                        <div class="card shadow ">
                            <div class="card-body p-2 px-3">
                                <p class="m-0 text-center font-bold fs-4">
                                    {{ $procurements->where('procurement_status', $status[0])->count() }}
                                </p>
                            </div>
                            <div class="card-footer bg-{{ $status[2] }} p-2 px-3">
                                <p class="text-white m-0 font-semibold text-center text-uppercase">
                                    Pembelian {{ $status[1] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Daftar Pembelian</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#procurement-detail"><i class="fas fa-plus"></i> <span
                                class="ml-2">Tambah
                                Pembelian</span> </button>
                    </div>
                </div>
                <div class="card-body">
                    <x-procurements.procurement-modal />
                    <x-procurements.procurement-data />
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')

@endsection
