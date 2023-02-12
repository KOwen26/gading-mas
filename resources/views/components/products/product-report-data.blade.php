<div class='table-responsive'>
    <table class="table table-hover table-striped" id="product-report-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Perusahaan</th>
                <th>Jumlah Terjual</th>
                <th>Total Pendapatan</th>
                {{-- <th>Harga Jual</th> --}}
                {{-- <th>Status</th> --}}
                {{-- <th>Aksi</th> --}}
            </tr>
        </thead>
        <tbody>
            {{-- {{ dd($products) }} --}}
            @if ($products->count() > 0)
            @foreach ($products as $product)
            <tr>
                <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                <td>{{ $product->product_name ?? '-' }}</td>
                <td><span class="badge
                            @if ($product?->companies?->company_id == 1) bg-success
                            @else
                            bg-info @endif
                            ">{{ $product?->companies?->company_name ?? '-' }}</span>
                </td>
                <td data-order="{{ intval($product?->total_quantity) }}">
                    {{ $product?->total_quantity ?? 0 }} pcs</td>
                <td data-order="{{ intval($product?->total_sold) }}">Rp
                    {{ number_format($product->total_sold ?? 0, '0', '0', '.') }}</td>
                {{-- <td data-order="{{ intval($product?->product_sell_price) }}">Rp
                    {{ number_format($product->product_sell_price ?? 0, '0', '0', '.') }}</td> --}}
                {{-- <td>
                    <span class="badge
                            @if ($product->product_status == 'ACTIVE') bg-success @else bg-danger @endif
                            ">
                        {{ $product->product_status }}</span>
                </td> --}}
                {{-- <td class="">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-warning" product_id="{{ $product->product_id }}"
                            onclick="ProductMovement(this)" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Pergerakan Produk"><i class="fas fa-arrow-right-arrow-left"></i></button>
                        <button type="button" class="btn btn-info" product_id="{{ $product->product_id }}"
                            onclick="Details(this)" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Detail Produk"><i class="fas fa-info"></i></button>
                        <button type="button" class="btn btn-danger" onclick="Delete(this)"
                            product_id="{{ $product?->product_id }}" product_name="{{ $product->product_name }}"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Produk"><i
                                class="fas fa-trash"></i></button>
                    </div>
                </td> --}}
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="8" class="font-bold text-center">Tidak ada data</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
<div id="modal-detail">

</div>
@push('script')
<script>
    $(document).ready(function() {
            $('#product-report-table').DataTable({
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ]
            });
        });

        function Details(elem) {
            let url = "{{ route('product.details', [':id']) }}"
            let product_id = elem.getAttribute("product_id")
            url = url.replace(':id', product_id)
            $.ajax({
                url,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#product-detail").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }
</script>
@endpush
