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
                <th>Nama Pemasok</th>
                <th>Tanggal Transaksi</th>
                <th>Jatuh Tempo Pembayaran</th>
                <th>Macam & Jumlah Produk</th>
                <th>Grand Total Transaksi</th>
                <th>Status Transaksi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($procurements->where('company_id', $company->company_id)->count() > 0)
            @foreach ($procurements->where('company_id', $company->company_id) as $procurement)
            <tr>
                <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                <td>
                    <span class="badge @if($procurement?->procurement_audit=='true') bg-info @else bg-secondary @endif">
                        {{ $procurement->procurement_id ?? '-' }}
                    </span>
                </td>
                <td>{{ $procurement->suppliers->supplier_name ?? '-' }}</td>
                <td>{{ $procurement->procurement_date ?? '-' }}</td>
                <td>{{ $procurement->procurement_due_date ?? '-' }}</td>
                <td data-order="{{ intval($procurement?->ProcurementDetails()->sum('product_quantity') ?? 0) }}">
                    {{ $procurement?->ProcurementDetails()->count() }}
                    ({{ $procurement?->ProcurementDetails()->sum('product_quantity') }} pcs)
                </td>
                <td data-order="{{ intval($procurement->procurement_grand_total ?? 0) }}">Rp
                    {{ number_format($procurement->procurement_grand_total ?? 0, '0', '0', '.') }}</td>
                <td>
                    <span class="badge
                    @if ($procurement?->procurement_status == 'FINISHED') bg-success @else bg-info @endif ">{{
                        $procurement?->procurement_status }}</span>
                    <span
                        class=" badge @if ($procurement?->payment_status == 'PAID') bg-success @else bg-danger @endif ">{{
                        $procurement?->payment_status }}</span>
                </td>
                <td class="">
                    <div class=" d-flex gap-2">
                        <form action="{{ route('procurement.print', ['id' => $procurement?->procurement_id]) }}"
                            target="_blank" method="get">
                            <button type="submit" class="btn btn-warning" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Cetak Invoice"><i class="fas fa-print"></i></button>
                        </form>
                        <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Detil Transaksi" onclick="Details(this)"
                            procurement_id="{{ $procurement->procurement_id }}"><i class="fas fa-info"></i></button>
                        @if ( auth()->user()->user_type==='OWNER')
                        <button type="button" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                            onclick="Delete(this)" procurement_id="{{ $procurement->procurement_id }}"
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

        function Delete(elem) {
            let url = "{{ route('procurement.delete') }}"
            let procurement_id = elem.getAttribute("procurement_id")
            let procurement_name = `Transaksi ${procurement_id}`
            // let procurement_name = elem.getAttribute("procurement_name")

            $.ajax({
                url,
                method: "GET",
                data: {
                    id: procurement_id,
                    name: procurement_name
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
