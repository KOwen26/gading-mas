<div class="modal fade text-left w-100" id="product-movement" role="dialog" aria-labelledby="product-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="product-modal">
                    Pergerakan / Perpindahan Produk
                </h4>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i style="font-size:1.25rem" class="fas fa-times w-5 "></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>Nama Produk : </b>{{ $product->product_name }}, <span><b>Sisa Stok : </b>{{
                                $product->product_quantity }} pcs </span></p>
                        <div class='table-responsive'>
                            <table class="table table-hover table-striped" id="table-movement">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Jenis Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Jumlah Produk</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($product_movement->count() > 0)
                                    @foreach ($product_movement as $product)
                                    <tr>
                                        <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                                        <td>{{ $product->transaction_id ?? '-' }}</td>
                                        <td>
                                            <span class="badge
                                                    @if($product->transaction_type == 'TRANSACTION') bg-success
                                                    @else bg-info @endif
                                                    ">{{ $product->transaction_type=='TRANSACTION' ?'Penjualan':
                                                'Pembelian' }}</span>
                                        </td>
                                        <td>{{ date('d-M-Y H:i',strtotime($product->transaction_date)) ?? '-' }}</td>
                                        <td
                                            class="
                                        @if ($product->product_movement== 'IN') text-success @else text-danger @endif ">
                                            {{ $product->product_movement== 'IN'?'+':'-' }}{{ $product->product_quantity
                                            ?? 0
                                            }} pcs</td>
                                        <td>
                                            @if ($product->transaction_type=='TRANSACTION')
                                            Penjualan Kepada <b>{{ $product->vendor_name }}</b>
                                            @else
                                            Pembelian Dari <b>{{ $product->vendor_name }}</b>
                                            @endif

                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6" class="font-bold text-center">Tidak ada data</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Tutup</span>
                </button>
            </div>
        </div>
    </div>
</div>
