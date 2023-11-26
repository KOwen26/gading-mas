<!DOCTYPE html>
<html lang="en">
@php
    setlocale(LC_ALL, 'id-ID', 'id_ID');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gading Mas Unggul - {{ $procurement?->procurement_id }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-datatables/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<style>
    .fs-7 {
        font-size: 14px;
    }

    .fs-8 {
        font-size: 12px;
    }
</style>

<body class="bg-white" onload="window.print()">
    <div class="container position-relative">
        <img class="position-absolute start-50 translate-middle"
            src="{{ asset('assets/images/logo/' . $procurement?->Companies?->company_picture) }}"
            style="width: 280px; top:50%; opacity: 12%; object-fit:cover; filter:grayscale(20%)" alt=""
            srcset="">
        <div class="row mt-2">
            <div class="col-12">
                <p class="fs-5 mb-0 font-bold text-uppercase text-center">
                    Permintaan Barang</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-2">
                <img src="{{ asset('assets/images/logo/' . $procurement?->Companies?->company_picture) }}"
                    style="width:100%; object-fit:cover" alt="" srcset="">
            </div>
            <div class="col-10">
                <div class="row mb-1">
                    <div class="col-7">
                        {{-- <p class="mb-0" style="">
                            <span class="font-bold text-uppercase">
                                {{ $procurement?->Companies?->company_name }}
                            </span>
                            <br>
                            {{ $procurement?->Companies?->company_phone }} |
                            {{ $procurement?->Companies?->company_email }}
                        </p> --}}
                    </div>
                    <div class="col-5">
                        <p class="mb-0 text-end">
                            <span class="font-bold">
                                {{ $procurement?->procurement_id }}
                            </span>
                            <br>
                            {{-- %A, %d %B %Y %H:%M --}}
                            {{ strftime('%d %B %Y %H:%M', strtotime($procurement?->procurement_date)) }}
                            WIB
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="d-block fs-7">
                            <span class="font-bold">Permintaan Kepada : </span>
                            <br>
                            <span class="font-bold">
                                {{ $procurement?->Suppliers?->supplier_name }}
                            </span>
                            <br>
                            {{ $procurement?->Suppliers?->supplier_phone }}
                            {{-- {{ $procurement?->Suppliers?->supplier_address }} --}}
                        </p>
                    </div>
                    <div class="col-6">
                        <p class="d-block fs-7">
                            <span class="font-bold">Dikirim Kepada : </span>
                            <br>
                            <span class="font-bold">
                                {{ $procurement?->Companies?->company_name }}
                            </span>
                            {{-- ({{
                            $procurement?->Companies?->company_phone }}) --}}
                            <br>
                            {{ $procurement?->Companies?->company_address }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="position-relative row mt-2">
            <div class="col-12">
                <table class="table table-sm table-bordered fs-7">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($procurement?->procurementDetails as $procurement_detail)
                            <tr>
                                <th> {{ $loop->iteration }}. </th>
                                <td> {{ $procurement_detail?->product_name }} </td>
                                <td class="text-center font-monospace fs-6">
                                    {{ $procurement_detail?->product_quantity }}
                                    {{ $procurement_detail?->product_unit ?: 'pcs' }}</td>
                                <td class="text-end font-monospace fs-6"> Rp
                                    {{ number_format($procurement_detail?->product_price, '0', '0', '.') }}
                                </td>
                                <td class="text-end font-monospace fs-6"> Rp
                                    {{ number_format($procurement_detail?->product_quantity * $procurement_detail?->product_price, '0', '0', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($procurement?->payment_status === 'PAID')
                <img class="position-absolute" src="{{ asset('assets/images/lunas.png') }}"
                    style="width:220px; right:80px; bottom:-60px; opacity: 24%;" alt="" srcset="">
            @endif
        </div>
        <div class="row fs-7" style="border-bottom:1px solid #222">
            <div class="col-6">
                <span class="  font-bold">
                    Catatan Pembelian :
                </span>
                <p style="line-height: 150%;">
                    {{ $procurement?->procurement_notes ?? '-' }}
                </p>
                <span class="  font-bold">
                    Keterangan :
                </span>
                <p class="" style="line-height: 150%;">
                    -
                    {{-- Pembayaran bisa melalui rekening
                    <br>
                    Panin Bank
                    <br>
                    a/n CV Gading Mas Unggul
                    <br>
                    XXXX.XXXX.XXXX --}}
                </p>
            </div>
            <div class="col-6">
                <p class="float-end text-end font-monospace fs-6" style="line-height: 150%;">
                    <span> Total Penjualan :</span>
                    <span class="d-inline-block" style="min-width:120px;">Rp
                        {{ number_format($procurement?->procurement_total, '0', '0', '.') }}</span>
                    <br>
                    <span> Potongan / Diskon : </span>
                    <span class="d-inline-block" style="min-width:120px;">Rp
                        {{ number_format($procurement?->procurement_discount, '0', '0', '.') }}</span>
                    <br>
                    <span> PPN (11%) :</span>
                    <span class="d-inline-block" style="min-width:120px;">
                        Rp {{ number_format($procurement?->procurement_tax, '0', '0', '.') }}</span>
                    <br>
                    <span class="font-bold"> Grand Total : </span> <span class="d-inline-block font-bold"
                        style="min-width:120px;">Rp
                        {{ number_format($procurement?->procurement_grand_total, '0', '0', '.') }}</span>
                </p>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
