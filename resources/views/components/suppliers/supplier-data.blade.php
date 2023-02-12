<div class='table-responsive'>
    <table class="table table-hover table-striped" id="table1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemasok</th>
                <th>No Telp</th>
                <th>Email</th>
                <th>Asal Kota</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($suppliers->count() > 0)
            @foreach ($suppliers as $supplier)
            <tr>
                <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                <td>{{ $supplier->supplier_name ?? '-' }} ({{ $supplier->supplier_contact_name ?? '-' }})
                </td>
                <td>{{ $supplier->supplier_phone ?? '-' }}</td>
                <td>{{ $supplier->supplier_email ?? '-' }}</td>
                <td>{{ $supplier->Cities->city_name ?? '-' }}</td>
                <td>{{ (strlen($supplier?->supplier_address) > 24? substr($supplier?->supplier_address, 0, 24) . '...':
                    $supplier?->supplier_address) ?? '-' }}</span>
                </td>
                <td class="">
                    <div class="d-flex gap-2">
                        @if ( auth()->user()->user_type==='OWNER')
                        <button type="button" class="btn btn-warning" supplier_id="{{ $supplier->supplier_id }}"
                            onclick="SupplierTransaction(this)" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Transaksi Pemasok"><i class="fas fa-receipt"></i></button>
                        @endif
                        <button type="button" class="btn btn-info" onclick="Details(this)"
                            supplier_id="{{ $supplier->supplier_id }}" title=" Detil Produk"><i
                                class="fas fa-info"></i></button>
                        @if ( auth()->user()->user_type==='OWNER')
                        <button type="button" class="btn btn-danger" onclick="Delete(this)"
                            supplier_id="{{ $supplier->supplier_id }}" supplier_name="{{ $supplier->supplier_name }}"
                            title="Hapus Produk"><i class="fas fa-trash"></i></button>
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
<div id="modal-detail">

</div>
<div id="modal-delete">

</div>
@push('script')
<script>
    $(document).ready(function() {
            $('#table1').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ]
            });
        });

        function SupplierTransaction(elem) {
            let url = "{{ route('supplier.transaction', [':id']) }}"
            let supplier_id = elem.getAttribute("supplier_id")
            url = url.replace(':id', supplier_id)
            $.ajax({
                url,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // async : false,
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#supplier-transaction").modal('show');
                    $('#table-transaction').DataTable({
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ]
                    });
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }

        function Details(elem) {
            let url = "{{ route('supplier.details', [':id']) }}"
            let supplier_id = elem.getAttribute("supplier_id")
            url = url.replace(':id', supplier_id)
            $.ajax({
                url,
                method: "GET",
                // data: {
                //     id: supplier_id
                // },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#supplier-detail").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }

        function Delete(elem) {
            let url = "{{ route('supplier.delete') }}"
            let supplier_id = elem.getAttribute("supplier_id")
            let supplier_name = elem.getAttribute("supplier_name")
            console.log(url, supplier_id, supplier_name)
            $.ajax({
                url,
                method: "GET",
                data: {
                    id: supplier_id,
                    name: supplier_name
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
