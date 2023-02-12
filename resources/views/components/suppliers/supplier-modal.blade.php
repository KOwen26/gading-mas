@php
$id = null;
if ($route == 'supplier.update') {
    $id = ['id' => $supplier->supplier_id];
}
@endphp

<form method="POST" action="{{ route($route, $id) }}">
    @if ($route == 'supplier.update')
        @method('put')
    @endif

    <div class="modal fade text-left w-100" id="supplier-detail" role="dialog" aria-labelledby="supplier-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="supplier-modal">
                        @if ($route != 'supplier.update')
                            Tambah Supplier Baru
                        @else
                            Perbarui Data Supplier
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
                            <div class="form-group"><label for="" class="text-bold">Nama Supplier</label>
                                <input type="text" class="form-control" name="supplier_name" id="supplier_name"
                                    placeholder="CV Gading" value="{{ $supplier?->supplier_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Nomor Telepon</label>
                                <input type="text" class="form-control" name="supplier_phone" id="supplier_phone"
                                    placeholder="628123456789"
                                    value="{{ old('supplier_phone', $supplier?->supplier_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Email</label>
                                <input type="email" class="form-control" name="supplier_email" id="supplier_email"
                                    placeholder="supplier@gmail.com"
                                    value="{{ old('supplier_email', $supplier?->supplier_email) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Nama Kontak
                                    Supplier</label>
                                <input type="text" class="form-control" name="supplier_contact_name"
                                    id="supplier_contact_name" placeholder="Bpk Tarjo"
                                    value="{{ old('supplier_contact_name', $supplier?->supplier_contact_name) }}">
                            </div>
                            <div class="form-group"><label for="" class="text-bold">Kota Supplier</label>
                                <div class="block">
                                    <input type="hidden" id="city_id" value={{ $supplier->city_id ?? '-' }}>
                                    @if ($route == 'supplier.update')
                                        <select class="form-control"
                                            style="width:100%;height: 38px; border:1px solid #dce7f1" id="select2js2"
                                            onclick="console.log($('#select2js2').select2())" name="city_id">
                                            @if ($cities)
                                                <option value="">Pilih Kota</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city['city_id'] }}"
                                                        @if ($supplier?->city_id == $city['city_id']) selected="selected" @endif>
                                                        {{ $city['city_name'] }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @else
                                        <select class="form-control"
                                            style="width:100%;height: 38px; border:1px solid #dce7f1" id="select2js"
                                            name="city_id">
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group"><label for="" class="text-bold">Alamat Supplier</label>
                                <textarea class="form-control" name="supplier_address" id="supplier_address" rows="4"
                                    placeholder="Kapas Krampung, Surabaya">{{ old('supplier_address', $supplier?->supplier_address) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"><label for="" class="text-bold">Catatan
                                    Supplier</label>
                                <textarea class="form-control" name="supplier_notes" id="supplier_notes" rows="2"
                                    placeholder="Buka setiap hari">{{ old('supplier_notes', $supplier?->supplier_notes) }}</textarea>
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
@push('script')
    <script>
        // let url = "{{ route('cities.show', [':id']) }}"
        let city_id = document.getElementById("city_id").value
        // url = url.replace(':id', city_id)
        // console.log(city_id, url)

        $(document).ready(function() {
            $('#select2js').select2({
                width: 'resolve', // need to override the changed default
                placeholder: 'Pilih Kota',
                // theme: 'bootstrap',
                ajax: {
                    url: "{{ route('cities.search') }}",
                    dataType: 'json',
                    // headers: {
                    //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // },
                    delay: 250,
                    data: function(params) {
                        var query = {
                            search: params.term,
                            type: 'query'
                        }
                        return query;
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.city_name,
                                    id: item.city_id
                                }
                            })
                        };
                    },
                    cache: true
                }
            })
            // .on("select2:select", function(e) {
            //     var selected = e.params.data;
            //     console.log("Selected", selected)
            //     if (typeof selected !== "undefined") {
            //         $("[name='city_id']").val(selected.id);
            //     }
            // }).on("select2:unselecting", function(e) {
            //     // $("form").each(function() {
            //     //     this.reset()
            //     // });
            //     // ("#allocationsDiv").hide();
            //     $("[name='city_id']").val("");
            // })
            // .then(function(data) {
            //     console.log(data)
            //     // var option = new Option(data.city_name, data.city_id, true, true);
            //     // select2.append(option).trigger('change');
            // })
            // .val(city_id).trigger('change');
            $('#select2js2').select2({
                width: 'resolve', // need to override the changed default
                placeholder: 'Pilih Kota',
                // theme: 'bootstrap',
            }).val(city_id).trigger('change')
        });





        // Fetch the preselected item, and add to the control
        // let select2 = $('#select2js');
        // $.ajax({
        //     type: 'GET',
        //     url
        // }).then(function(data) {
        //     // create the option and append to Select2
        // var option = new Option(data.city_name, data.city_id, true, true);
        // select2.append(option).trigger('change');

        //     // manually trigger the `select2:select` event
        //     select2.trigger({
        //         type: 'select2:select',
        //         params: {
        //             data: data
        //         }
        //     });
        // });
    </script>
@endpush
