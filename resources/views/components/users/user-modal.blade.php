@php
$id = null;
if ($route == 'user.update') {
    $id = ['id' => $user->user_id];
}
@endphp

<form method="POST" action="{{ route($route, $id) }}">
    @if ($route == 'user.update')
        @method('put')
    @endif

    <div class="modal fade text-left w-100" id="user-detail" role="dialog" aria-labelledby="user-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="user-modal">
                        @if ($route != 'user.update')
                            Tambah Pengguna Baru
                        @else
                            Perbarui Data Pengguna
                        @endif
                    </h4>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i style="font-size:1.25rem" class="fas fa-times w-5 "></i>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- {{ var_dump($cities ?? '') }} --}}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Nama user</label>
                                <input type="text" class="form-control" name="user_name" id="user_name"
                                    placeholder="CV Gading" value="{{ $user?->user_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Nomor Telepon</label>
                                <input type="text" class="form-control" name="user_phone" id="user_phone"
                                    placeholder="628123456789" value="{{ old('user_phone', $user?->user_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Email</label>
                                <input type="email" class="form-control" name="user_email" id="user_email"
                                    placeholder="user@gmail.com" value="{{ old('user_email', $user?->user_email) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Password Lama</label>
                                <input type="password" class="form-control" name="old_password" id="old_password"
                                    placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Password Baru</label>
                                <input type="password" class="form-control" name="new_password" id="new_password"
                                    placeholder="" value="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Ulangi Password
                                    Baru</label>
                                <input type="password" class="form-control" name="new_password_repeat"
                                    id="new_password_repeat" placeholder="" value="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    @csrf
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
