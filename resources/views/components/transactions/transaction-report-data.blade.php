@foreach ($companies as $company)
    <h6 class="mt-5 mb-2">
        {{ $company->company_name }}
    </h6>
    <div class='table-responsive'>
        <table class="table table-hover table-striped" id="table{{ $loop->iteration }}">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Periode Bulan - Tahun</th>
                    <th>Macam Produk</th>
                    <th>Total Jumlah Produk</th>
                    <th>Total Jumlah Transaksi</th>
                    <th>Grand Total Pendapatan</th>
                    <th>Status Transaksi</th>
                    {{-- <th>Aksi</th> --}}
                </tr>
            </thead>
            <tbody>
                @if ($transaction_report->where('company_id', $company->company_id)->count() > 0)
                    @foreach ($transaction_report->where('company_id', $company->company_id) as $transaction)
                        <tr>
                            <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                            <td>{{ Str::length($transaction->month) == 1 ? "0$transaction->month" : $transaction->month }}-{{ $transaction->year }}
                            </td>
                            <td data-order="{{ intval($transaction?->product_quantity) }}">
                                {{ $transaction->product_quantity ?? 0 }} Produk</td>
                            <td data-order="{{ intval($transaction?->product_total_quantity) }}">
                                {{ $transaction->product_total_quantity ?? 0 }} pcs</td>
                            <td data-order="{{ intval($transaction?->transaction_quantity ?? 0) }}">
                                {{ $transaction->transaction_quantity ?? '-' }} Transaksi</td>
                            <td data-order="{{ intval($transaction?->transaction_grand_total ?? 0) }}">Rp
                                {{ number_format($transaction->transaction_grand_total ?? 0, '0', '0', '.') }}</td>
                            <td>
                                <span
                                    class="badge
                                    @if ($transaction?->transaction_status == 'FINISHED') bg-success @else bg-info @endif ">{{ $transaction?->transaction_status }}</span>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="font-bold text-center">Tidak ada data</td>
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
        // Simple Datatable
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
    </script>
@endpush
