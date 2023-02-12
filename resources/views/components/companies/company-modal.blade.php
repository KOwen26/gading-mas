@php
$id = null;
if ($route == 'company.update') {
$id = ['id' => $company->company_id];
}
@endphp

<form method="POST" action="{{ route($route, $id) }}">
    @if ($route == 'company.update')
    @method('put')
    @endif

    <div class="modal fade text-left w-100" id="company-detail" role="dialog" aria-labelledby="company-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="company-modal">
                        @if ($route != 'company.update')
                        Tambah Perusahaan Baru
                        @else
                        Perbarui Data Perusahaan
                        @endif
                    </h4>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i style="font-size:1.25rem" class="fas fa-times w-5 "></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Nama company</label>
                                <input type="text" class="form-control" name="company_name" id="company_name"
                                    placeholder="CV Gading" value="{{ $company?->company_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Nomor Telepon</label>
                                <input type="text" class="form-control" name="company_phone" id="company_phone"
                                    placeholder="628123456789"
                                    value="{{ old('company_phone', $company?->company_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Email</label>
                                <input type="email" class="form-control" name="company_email" id="company_email"
                                    placeholder="company@gmail.com"
                                    value="{{ old('company_email', $company?->company_email) }}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"><label for="" class="text-bold">Alamat</label>
                                <textarea class="form-control" name="company_address" id="company_address" cols="30"
                                    rows="2">{{ $company?->company_address }}</textarea>
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
