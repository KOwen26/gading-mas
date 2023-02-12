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
                    <th>Grand Total Pengeluaran</th>
                    <th>Status Transaksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($procurement_report->where('company_id', $company->company_id)->count() > 0)
                    @foreach ($procurement_report->where('company_id', $company->company_id) as $procurement)
                        <tr>
                            <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                            <td>{{ Str::length($procurement->month) == 1 ? "0$procurement->month" : $procurement->month }}-{{ $procurement->year }}
                            </td>
                            <td data-order="{{ intval($procurement?->product_quantity) }}">
                                {{ $procurement->product_quantity ?? 0 }} Produk</td>
                            <td data-order="{{ intval($procurement?->product_total_quantity) }}">
                                {{ $procurement->product_total_quantity ?? 0 }} pcs</td>
                            <td data-order="{{ intval($procurement?->procurement_quantity) }}">
                                {{ $procurement->procurement_quantity ?? 0 }} Transaksi</td>
                            <td data-order="{{ intval($procurement?->procurement_grand_total) }}">Rp
                                {{ number_format($procurement->procurement_grand_total ?? 0, '0', '0', '.') }}</td>
                            <td>
                                <span
                                    class="badge
                                    @if ($procurement?->procurement_status == 'FINISHED') bg-success @else bg-info @endif ">{{ $procurement?->procurement_status }}</span>
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
            let url = "{{ route('procurement.details', [':id']) }}"
            let procurement_id = elem.getAttribute("procurement_id")
            url = url.replace(':id', procurement_id)
            $.ajax({
                url,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#procurement-detail").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }
    </script>
@endpush
