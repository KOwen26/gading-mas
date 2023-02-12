@php
$id = null;
if ($route == 'customer.update') {
    $id = ['id' => $customer->customer_id];
}
@endphp

<form method="POST" action="{{ route($route, $id) }}">
    @if ($route == 'customer.update')
        @method('put')
    @endif

    <div class="modal fade text-left w-100" id="customer-detail" role="dialog" aria-labelledby="customer-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="customer-modal">
                        @if ($route != 'customer.update')
                            Tambah Pelanggan Baru
                        @else
                            Perbarui Data Pelanggan
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
                            <div class="form-group"><label for="" class="text-bold">Nama Pelanggan</label>
                                <input type="text" class="form-control" name="customer_name" id="customer_name"
                                    placeholder="CV Gading" value="{{ $customer?->customer_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Nomor Telepon</label>
                                <input type="text" class="form-control" name="customer_phone" id="customer_phone"
                                    placeholder="628123456789"
                                    value="{{ old('customer_phone', $customer?->customer_phone) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label for="" class="text-bold">Email Pelanggan</label>
                                <input type="email" class="form-control" name="customer_email" id="customer_email"
                                    placeholder="customer@gmail.com"
                                    value="{{ old('customer_email', $customer?->customer_email) }}">
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group"><label for="" class="text-bold">Kota Pelanggan</label>
                                <div class="block">
                                    <input type="hidden" id="city_id" value={{ $customer->city_id ?? '-' }}>
                                    @if ($route == 'customer.update')
                                        <select class="form-control"
                                            style="width:100%;height: 38px; border:1px solid #dce7f1" id="select2js2"
                                            onclick="console.log($('#select2js2').select2())" name="city_id">
                                            @if ($cities)
                                                <option value="">Pilih Kota</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city['city_id'] }}"
                                                        @if ($customer?->city_id == $city['city_id']) selected="selected" @endif>
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
                            <div class="form-group"><label for="" class="text-bold">Alamat Pelanggan</label>
                                <textarea class="form-control" name="customer_address" id="customer_address" rows="2"
                                    placeholder="Kapas Krampung, Surabaya">{{ old('customer_address', $customer?->customer_address) }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group"><label for="" class="text-bold">Catatan
                                    Pelanggan</label>
                                <textarea class="form-control" name="customer_notes" id="customer_notes" rows="2"
                                    placeholder="Buka setiap hari">{{ old('customer_notes', $customer?->customer_notes) }}</textarea>
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
