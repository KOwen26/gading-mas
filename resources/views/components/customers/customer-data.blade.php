<div class='table-responsive'>
    <table class="table table-hover table-striped" id="table1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>No Telp</th>
                <th>Email</th>
                <th>Kota</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($customers->count() > 0)
            @foreach ($customers as $customer)
            <tr>
                <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                <td>{{ $customer->customer_name ?? '-' }}</td>
                <td>{{ $customer->customer_phone ?? '-' }}</td>
                <td>{{ $customer->customer_email ?? '-' }}</td>
                <td>{{ $customer->cities->city_name ?? '-' }}</td>
                <td>{{ (strlen($customer?->customer_address) > 24? substr($customer?->customer_address, 0, 24) . '...':
                    $customer?->customer_address) ?? '-' }}</span>
                <td class="">
                    <div class="d-flex gap-2">
                        @if ( auth()->user()->user_type==='OWNER')
                        <button type="button" class="btn btn-warning" customer_id="{{ $customer->customer_id }}"
                            onclick="CustomerTransaction(this)" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Transaksi Pelanggan"><i class="fas fa-receipt"></i></button>
                        @endif
                        <button type="button" class="btn btn-info" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Detil Produk" customer_id="{{ $customer->customer_id }}" onclick="Details(this)"><i
                                class="fas fa-info"></i></button>
                        @if ( auth()->user()->user_type==='OWNER')
                        <button type="button" class="btn btn-danger" customer_id="{{ $customer->customer_id }}"
                            customer_name="{{ $customer->customer_name }}" data-bs-toggle="tooltip"
                            onclick="Delete(this)" data-bs-placement="top" title="Hapus Produk"><i
                                class="fas fa-trash"></i></button>
                        @endif
                    </div>
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

        function CustomerTransaction(elem) {
            let url = "{{ route('customer.transaction', [':id']) }}"
            let customer_id = elem.getAttribute("customer_id")
            url = url.replace(':id', customer_id)
            $.ajax({
                url,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // async : false,
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#customer-transaction").modal('show');
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
            let url = "{{ route('customer.details', [':id']) }}"
            let customer_id = elem.getAttribute("customer_id")
            url = url.replace(':id', customer_id)
            $.ajax({
                url,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#customer-detail").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }

        function Delete(elem) {
            let url = "{{ route('customer.delete') }}"
            let customer_id = elem.getAttribute("customer_id")
            let customer_name = elem.getAttribute("customer_name")

            $.ajax({
                url,
                method: "GET",
                data: {
                    id: customer_id,
                    name: customer_name
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
