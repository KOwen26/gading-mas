@php
    $id = null;
    if ($route == 'product.update') {
        $id = ['id' => $product->product_id];
    }
    $statuses = ['ACTIVE', 'DISABLED'];
@endphp

<form method="POST" action="{{ route($route, $id) }}">
    @if ($route == 'product.update')
        @method('put')
    @endif

    <div class="modal fade text-left w-100" id="product-detail" role="dialog" aria-labelledby="product-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="product-modal">
                        @if ($route != 'product.update')
                            Tambah Produk Baru
                        @else
                            Perbarui Data Produk
                        @endif
                    </h4>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i style="font-size:1.25rem" class="fas fa-times w-5 "></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group"><label for="" class="text-bold">Nama Produk</label>
                                <input type="text" class="form-control" placeholder="Contoh : Minyak Goreng"
                                    name="product_name" value="{{ $product?->product_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Milik Perusahaan</label>
                                <select class="form-control" name="company_id" id="company_id">
                                    @if ($companies)
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->company_id }}">{{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Stok Produk</label>
                                <input type="number" name="product_quantity" id="product_quantity" min="0"
                                    class="form-control" placeholder="" value="{{ $product?->product_quantity }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="" class="text-bold">Satuan Produk</label>
                                <input type="text" name="product_unit" id="product_unit" min="0"
                                    class="form-control" placeholder="Contoh : pcs"
                                    value="{{ $product?->product_unit }}">
                            </div>
                        </div>
                        @if (auth()->user()->user_type === 'OWNER')
                            <div class="col-md-4">
                                <div class="form-group"><label for="" class="text-bold">Harga Beli
                                    </label>
                                    <input type="number" name="product_buy_price" step="100" id="product_buy_price"
                                        min="0" class="form-control" placeholder=""
                                        value="{{ $product?->product_buy_price }}">
                                </div>
                            </div>
                        @endif
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Harga Jual</label>
                                <input type="number" name="product_sell_price" step="100" id="product_sell_price"
                                    min="0" class="form-control" placeholder=""
                                    value="{{ $product?->product_sell_price }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Status Produk</label>
                                <select class="form-control" name="product_status" id="product_status">
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}"
                                            @if ($product?->product_status == $status) selected="selected" @endif>
                                            {{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group"><label for="" class="text-bold">Deskripsi Produk</label>
                                <textarea class="form-control" name="product_description" id="product_description" cols="30" rows="2">{{ $product?->product_description }}</textarea>
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
