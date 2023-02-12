<div class='table-responsive'>
    <table class="table table-hover table-striped" id="table1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>No Telp</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @if ($users->count() > 0)
            @foreach ($users as $user)
            <tr>
                <td class="font-bold text-center">{{ $loop->iteration }}.</td>
                <td>{{ $user->user_name ?? '-' }} </td>
                <td>{{ $user->user_phone ?? '-' }}</td>
                <td>{{ $user->user_email ?? '-' }}</td>
                <td class="">
                    <div class="d-flex gap-2">
                        <button type="button" {{-- @if ($user->user_id !== auth()->user()->user_id) disabled hidden
                            @endif --}}
                            class="btn btn-info" user_id="{{ $user->user_id }}" onclick="Details(this)"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Detil Pengguna"><i
                                class="fas fa-info"></i></button>
                        {{-- <button type="button" class="btn btn-danger" data-bs-toggle="tooltip"
                            data-bs-placement="top" title="Hapus Pengguna"><i class="fas fa-trash"></i></button> --}}
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
    // Simple Datatable
        $(document).ready(function() {
            $('#table1').DataTable({});
        });

        function Details(elem) {
            let url = "{{ route('user.details', [':id']) }}"
            let user_id = elem.getAttribute("user_id")
            url = url.replace(':id', user_id)
            $.ajax({
                url,
                method: "GET",
                // data: {
                //     id: user_id
                // },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    document.getElementById('modal-detail').innerHTML = data
                    $("#modal-detail").find("#user-detail").modal('show');
                },
                error: function(err, ecp) {
                    console.log(err)
                },
            })
        }
</script>
@endpush
