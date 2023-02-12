<form action="{{ route($route, ['id' => $id]) }}" method="POST">
    @method('delete')
    @csrf
    <div class="modal fade text-left" id="delete" tabindex="-1" role="dialog" aria-labelledby="label" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title white" id="label">
                        Hapus Data {{ $name }}
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menghapus <span class="font-bold">{{ $name }}</span> ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Batal</span>
                    </button>
                    <button type="submit" class="btn btn-danger ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Hapus</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
