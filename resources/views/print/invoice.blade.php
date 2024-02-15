<!DOCTYPE html>
<html lang="en">
@php
    setlocale(LC_ALL, 'id-ID', 'id_ID');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gading Mas Unggul - {{ $transaction?->transaction_id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&family=Azeret+Mono:wght@100;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
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
            src="{{ asset('assets/images/logo/' . $transaction?->Companies?->company_picture) }}"
            style="width: 280px; top:50%; opacity: 12%; object-fit:cover; filter:grayscale(20%)" alt=""
            srcset="">
        <div class="row mt-2">
            <div class="col-12">
                <p class="fs-5 mb-0 font-bold text-uppercase text-center">
                    Invoice</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-2">
                <img src="{{ asset('assets/images/logo/' . $transaction?->Companies?->company_picture) }}"
                    style="width:100%; object-fit:cover" alt="" srcset="">
            </div>
            <div class="col-10">
                <div class="row mb-1">
                    <div class="col-7">
                        <p class="mb-0" style="">
                            <span class="font-bold text-uppercase">
                                {{ $transaction?->Companies?->company_name }}
                            </span>
                            <br>
                            <!--{{ $transaction?->Companies?->company_phone }} |-->
                            {{ $transaction?->Companies?->company_email }}
                        </p>

                    </div>
                    <div class="col-5">
                        <p class="mb-0 text-end">
                            <span class="font-bold">
                                {{ $transaction?->transaction_id }}
                            </span>
                            <br>
                            {{-- %A, %d %B %Y %H:%M --}}
                            {{ strftime('%d %B %Y %H:%M', strtotime($transaction?->transaction_date)) }}
                            WIB
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="d-block fs-7">
                            <span class="font-bold">Dijual / Dikirim Kepada : </span>
                            <br>
                            <span class="font-bold">
                                {{ $transaction?->Customers?->customer_name }}
                            </span>
                            {{-- ({{
                            $transaction?->Customers?->customer_phone }}) --}}
                            <br>
                            {{ $transaction?->Customers?->customer_address }}
                        </p>
                    </div>
                    <div class="col-6">
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
                        @foreach ($transaction?->TransactionDetails as $transaction_detail)
                            <tr>
                                <th> {{ $loop->iteration }}. </th>
                                <td> {{ $transaction_detail?->product_name }} </td>
                                <td class="text-center fs-6" style="font-family: 'Courier Prime', 'Azeret Mono', monospace;"> {{ $transaction_detail?->product_quantity }}
                                    {{ $transaction_detail?->product_unit ?: 'pcs' }} </td>
                                <td class="text-end fs-6" style="font-family: 'Courier Prime', 'Azeret Mono', monospace;"> Rp
                                    {{ number_format($transaction_detail?->product_price, '0', '0', '.') }}
                                </td>
                                <td class="text-end fs-6" style="font-family: 'Courier Prime', 'Azeret Mono', monospace;"> Rp
                                    {{ number_format($transaction_detail?->product_quantity * $transaction_detail?->product_price, '0', '0', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($transaction?->payment_status === 'PAID')
                <img class="position-absolute" src="{{ asset('assets/images/lunas.png') }}"
                    style="width:220px; right:80px; bottom:-60px; opacity: 24%;" alt="" srcset="">
            @endif
        </div>
        <div class="row fs-7" style="border-bottom:1px solid #222">
            <div class="col-6">
                <span class="  font-bold">
                    Catatan Pembeli :
                </span>
                <p style="line-height: 150%;">
                    {{ $transaction?->transaction_notes ?? '-' }}
                </p>
                <span class="font-bold">
                    Keterangan :
                </span>
                <p class="mb-1">
                    Pembayaran bisa melalui rekening:
                </p>
                <div class="row" style="line-height: 150%;">
                    <div class="col-auto">
                        <p class="">
                            <strong>Panin Bank </strong>
                            <br>
                            a/n CV Gading Mas Unggul
                            <br>
                            469-200-8282
                        </p>
                    </div>
                    <div class="col-auto">
                        <!--<p class="">-->
                        <!--    <strong>BCA </strong>-->
                        <!--    <br>-->
                        <!--    a/n Iis Ekowati-->
                        <!--    <br>-->
                        <!--    468-030-0378-->
                        <!--</p>-->
                    </div>
                </div>
            </div>
            <div class="col-6">
                <p class="float-end text-end fs-6" style="line-height: 150%; font-family: 'Courier Prime', 'Azeret Mono', monospace;">
                    <span> Total Penjualan :</span>
                    <span class="d-inline-block" style="min-width:120px;">Rp
                        {{ number_format($transaction?->transaction_total, '0', '0', '.') }}</span>
                    <br>
                    <span> Potongan / Diskon : </span>
                    <span class="d-inline-block" style="min-width:120px;">Rp
                        {{ number_format($transaction?->transaction_discount, '0', '0', '.') }}</span>
                    <br>
                    <span> PPN (11%) :</span>
                    <span class="d-inline-block" style="min-width:120px;">
                        Rp {{ number_format($transaction?->transaction_tax, '0', '0', '.') }}</span>
                    <br>
                    <span class="font-bold"> Grand Total : </span> <span class="d-inline-block font-bold"
                        style="min-width:120px;">Rp
                        {{ number_format($transaction?->transaction_grand_total, '0', '0', '.') }}</span>
                    <br>
                    <span class="d-inline-block font-bold mt-2" style="width:400px;">
                        <!--<span class="">Penjual</span>-->
                        <span class="" style="margin-left:100px; margin-right:20px">Hormat Kami</span></span>
                <!--<div class="row" style="height: 120px;" >-->
                <!--    <div class="col-6 text-center">Penjual</div>-->
                <!--    <div class="col-6 text-center">Pembeli</div>-->
                </div> 
                </p>
                <!--<p class="float-end">-->
                <!--</p>-->
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
