@foreach ($companies as $company)
<h6 class="mt-5 mb-2">
    {{ $company->company_name }}
</h6>
<div class='table-responsive'>
    <table class="table table-hover table-striped" id="table{{ $loop->iteration }}">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Transaksi</th>
                <th>Nama Pelanggan</th>
                <th>Tanggal Transaksi</th>
                <th>Jatuh Tempo Pembayaran</th>
                <th>Macam & Jumlah Produk</th>
                <th>Grand Total Transaksi</th>
                <th>Status Transaksi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($transactions->where('company_id', $company->company_id)->count() > 0)
            @foreach ($transactions->where('company_id', $company->company_id) as $transaction)
            <tr>
                <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                <td>
                    <span class="badge @if($transaction?->transaction_audit=='true') bg-info @else bg-secondary @endif">
                        {{ $transaction->transaction_id ?? '-' }}
                    </span>
                </td>
                <td>{{ $transaction->customers->customer_name ?? '-' }}</td>
                <td>{{ $transaction->transaction_date ?? '-' }}</td>
                <td>{{ $transaction->transaction_due_date ?? '-' }}</td>
                <td data-order="{{ intval($transaction?->TransactionDetails()->sum('product_quantity') ?? 0) }}">
                    {{ $transaction?->TransactionDetails()->count() }}
                    ({{ $transaction?->TransactionDetails()->sum('product_quantity') }} pcs)
                </td>
                <td data-order="{{ intval($transaction?->transaction_grand_total ?? 0) }}">Rp
                    {{ number_format($transaction->transaction_grand_total ?? 0, '0', '0', '.') }}</td>
                <td>
                    <span class="badge
                    @if ($transaction?->transaction_status == 'FINISHED') bg-success @else bg-info @endif ">{{
                        $transaction?->transaction_status }}</span>
                    <span
                        class=" badge @if ($transaction?->payment_status == 'PAID') bg-success @else bg-danger @endif ">{{
                        $transaction?->payment_status }}</span>
                </td>
                <td class="">
                    <div class=" d-flex gap-2">
                        <form action="{{ route('transaction.print', ['id' => $transaction->transaction_id]) }}"
                            target="_blank" method="get">
                            <button type="submit" class="btn btn-warning" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Cetak Invoice"><i class="fas fa-print"></i></button>
                        </form>
                        <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Detil Transaksi" onclick="Details(this)"
                            transaction_id="{{ $transaction->transaction_id }}"><i class="fas fa-info"></i></button>
                        @if (auth()->user()->user_type==='OWNER')
                        <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                            onclick="Delete(this)" transaction_id="{{ $transaction->transaction_id }}"
                            title="Hapus Transaksi"><i class="fas fa-trash"></i></button>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="7" class="font-bold text-center">Tidak ada data</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endforeach
<div id="modal-detail">

</div>
<div id="modal-delete">

</div>
@push('script')
<script>
    $(document).ready(function() {
            let table_count = '{!! $companies->count() !!}'
            for (let index = 0; index < table_count; index++) {
                $(`#table${index+1}`).DataTable({
                    "aaSorting": [],
                    "lengthMenu": [
                        [20, 50, 100, -1],
                        [20, 50, 100, "All"]
                    ],
                })
            }
        });

        function Details(elem) {
            let url = "{{ route('transaction.details', [':id']) }}"
            let transaction_id = elem.getAttribute("transaction_id")
            url = url.replace(':id', transaction_id)
            $.ajax({
                url,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#transaction-detail").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }

        function Delete(elem) {
            let url = "{{ route('transaction.delete') }}"
            let transaction_id = elem.getAttribute("transaction_id")
            let transaction_name = `Transaksi ${transaction_id}`
            // let transaction_name = elem.getAttribute("transaction_name")

            $.ajax({
                url,
                method: "GET",
                data: {
                    id: transaction_id,
                    name: transaction_name
                },
                success: function(data) {
                    document.getElementById('modal-delete').innerHTML = data
                    $("#modal-delete").find("#delete").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }
</script>
@endpush
