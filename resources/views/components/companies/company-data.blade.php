<div class='table-responsive'>
    <table class="table table-hover table-striped" id="table1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Perusahaan</th>
                <th>No Telepon</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($companies->count() > 0)
                @foreach ($companies as $company)
                    <tr>
                        <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                        <td>{{ $company->company_name ?? '-' }}</td>
                        <td>{{ $company->company_phone ?? '-' }}</td>
                        <td>{{ $company->company_email ?? '-' }}</td>
                        <td>{{ $company->company_address ?? '-' }}</td>
                        <td class="">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-info" onclick="Details(this)"
                                    company_id="{{ $company->company_id }}" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Detil Perusahaan"><i
                                        class="fas fa-info"></i></button>
                                {{-- <button type="button" class="btn btn-danger" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Hapus Produk"><i class="fas fa-trash"></i></button> --}}
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

@push('script')
    <script>
        $(document).ready(function() {
            $('#table1').DataTable({});
        });

        function Details(elem) {
            let url = "{{ route('company.details', [':id']) }}"
            let company_id = elem.getAttribute("company_id")
            url = url.replace(':id', company_id)
            $.ajax({
                url,
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#company-detail").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }
    </script>
@endpush
