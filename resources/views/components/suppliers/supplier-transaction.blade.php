<div class="modal fade text-left w-100" id="supplier-transaction" role="dialog" aria-labelledby="transaction-modal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="transaction-modal">
                    Pembelian per Pemasok
                </h4>
                <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                    <i style="font-size:1.25rem" class="fas fa-times w-5 "></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <p><b>Nama Pemasok : </b>{{ $supplier->supplier_name }}</p>
                        <div class='table-responsive'>
                            <table class="table table-hover table-striped" id="table-transaction">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Macam Produk</th>
                                        <th>Total Jumlah Produk</th>
                                        <th>Grand Total Transaksi</th>
                                        <th>Status Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($supplier_transaction->count() > 0)
                                        @foreach ($supplier_transaction as $procurement)
                                            <tr>
                                                <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                                                <td>{{ $procurement?->procurement_id ?? '-' }}</td>
                                                <td>{{ date('d-M-Y H:i', strtotime($procurement?->procurement_date)) ?? '-' }}
                                                </td>
                                                <td
                                                    data-order="{{ intval($procurement?->ProcurementDetails()->count()) }}">
                                                    {{ $procurement?->ProcurementDetails()->count() ?? 0 }} produk
                                                </td>
                                                <td
                                                    data-order="{{ intval($procurement?->ProcurementDetails()->sum('product_quantity')) }}">
                                                    {{ $procurement?->ProcurementDetails()->sum('product_quantity') ?? 0 }}
                                                    pcs
                                                </td>

                                                <td data-order="{{ intval($procurement?->procurement_grand_total) }}">
                                                    Rp
                                                    {{ number_format($procurement?->procurement_grand_total ?? 0, '0', '0', '.') }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge @if ($procurement?->procurement_status == 'FINISHED') bg-success @else bg-info @endif ">{{ $procurement?->procurement_status }}</span>
                                                    <span
                                                        class=" badge @if ($procurement?->payment_status == 'PAID') bg-success @else bg-danger @endif ">{{ $procurement?->payment_status }}</span>
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
