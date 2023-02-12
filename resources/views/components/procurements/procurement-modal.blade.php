@php
if ($route == 'procurement.update') {
$id = ['id' => $procurement->procurement_id];
}
$statuses = [['NEW', 'Baru'], ['FINISHED', 'Selesai']];
$payment_status = [['PAID', 'Sudah Lunas'], ['UNPAID', 'Belum Dibayar']];
@endphp

<form method="POST" action="{{ route($route, $id) }}">
    @if ($route == 'procurement.update')
    @method('put')
    @endif
    <div class="modal fade text-left w-100" id="procurement-detail" role="dialog" aria-labelledby="procurement-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="procurement-modal">
                        @if ($route != 'procurement.update')
                        Tambah Pembelian Baru
                        @else
                        Perbarui Data Pembelian
                        @endif
                    </h4>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i style="font-size:1.25rem" class="fas fa-times w-5 "></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Kode Pembelian</label>
                                <input type="text" readonly class="form-control" placeholder="" name="procurement_id"
                                    value="{{ $procurement?->procurement_id ?? $id }}">
                                <input type="hidden" id="route" value="{{ $route }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Milik Perusahaan</label>
                                <select class="form-control" name="company_id" id="company_id">
                                    @if ($companies)
                                    @foreach ($companies as $company)
                                    <option value="{{ $company->company_id }}" @if ($procurement?->
                                        company_id==$company->company_id) selected @endif
                                        >{{ $company->company_name }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Tanggal Pembelian</label>
                                <input type="datetime-local" class="form-control" placeholder="" name="procurement_date"
                                    id="procurement_date"
                                    value="{{ $procurement?->procurement_date ? date('Y-m-d\TH:i:s', strtotime($procurement?->procurement_date)): date('Y-m-d\TH:i:s')  }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Jatuh Tempo Pembayaran</label>
                                <input type="date" class="form-control" placeholder="" name="procurement_due_date"
                                    id="procurement_due_date"
                                    value="{{ $procurement?->procurement_due_date ? date('Y-m-d', strtotime($procurement?->procurement_due_date)): date('Y-m-d')  }}">
                            </div>
                            <div class="form-group"><label for="" class="text-bold">Nama Supplier</label>
                                <select class="form-control" name="supplier_id" id="supplier_id" required>
                                    <option value="">Pilih Supplier</option>
                                    @if ($suppliers)
                                    @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" @if ($procurement?->
                                        supplier_id==$supplier->supplier_id) selected @endif>{{ $supplier->supplier_name
                                        }}
                                    </option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table" id="procurement_details">
                                    <thead class="">
                                        <tr>
                                            <th style="min-width: 400px">Nama Produk</th>
                                            <th>Jumlah Produk</th>
                                            <th>Harga Produk</th>
                                            <th>Subtotal Produk</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    @if ($procurement?->procurementDetails)
                                    <tbody id="procurement-product">
                                        @foreach ($procurement?->procurementDetails as $procurement_detail)
                                        <tr id="row{{ ($loop->iteration -1) }}">
                                            <td>
                                                <select class="form-control product-select2" name="product_id[]"
                                                    index="{{ ($loop->iteration -1) }}" id="product_id"
                                                    onchange="setPrice(this)">
                                                    @if ($products)
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($products as $product)
                                                    <option value="{{ $product->product_id }}"
                                                        @if($procurement_detail?->
                                                        product_id==$product->product_id) selected @endif
                                                        price="{{ $product->product_buy_price }}">
                                                        {{ $product->product_name }} ({{ $product->product_quantity
                                                        }})
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="product_quantity[]"
                                                    onchange="calculateUpdate()" id="product_quantity_update"
                                                    index="{{ ($loop->iteration -1) }}" min="0" max=""
                                                    class="form-control" placeholder=""
                                                    value="{{ $procurement_detail?->product_quantity ?? 0 }}">
                                            </td>
                                            <td>
                                                <input type="number" name="product_price[]" onchange="calculateUpdate()"
                                                    step="100" id="product_price_update"
                                                    index="{{ ($loop->iteration -1) }}" min="0" class="form-control"
                                                    placeholder=""
                                                    value="{{ $procurement_detail?->product_price ?? 0 }}">
                                            </td>
                                            <td>
                                                <input type="number" name="product_subtotal[]" step="100"
                                                    id="product_subtotal_update" index="{{ ($loop->iteration -1) }}"
                                                    min="0" class="form-control" placeholder=""
                                                    value="{{ ($procurement_detail?->product_quantity * $procurement_detail?->product_price) ?? 0 }}"
                                                    readonly>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if ($loop->iteration >1)
                                                    <button type="button" onclick="removeRow({{ $loop->iteration -1 }})"
                                                        index="{{ ($loop->iteration -1) }}" class="btn btn-danger"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    @else
                                    <tbody id="dynamic-procurement-product">
                                        <tr id="row0">
                                            <td>
                                                <select class="form-control product-select2" name="product_id[]"
                                                    index="0" id="product_id" onchange="setPrice(this)" required>
                                                    @if ($products)
                                                    <option value="">Pilih Produk</option>
                                                    @foreach ($products as $product)
                                                    <option value="{{ $product->product_id }}"
                                                        price="{{ $product->product_buy_price }}">
                                                        {{ $product->product_name }} ({{ $product->product_quantity
                                                        }})
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="product_quantity[]" onchange="calculate()"
                                                    id="product_quantity" index="0" min="0" class="form-control"
                                                    placeholder="" value="0">
                                            </td>
                                            <td>
                                                <input type="number" name="product_price[]" onchange="calculate()"
                                                    step="100" id="product_price" index="0" min="0" class="form-control"
                                                    placeholder="" value="0">
                                            </td>
                                            <td>
                                                <input type="number" name="product_subtotal[]" step="100"
                                                    id="product_subtotal" index="0" min="0" class="form-control"
                                                    placeholder="" value="0" readonly>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                    @endif
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <button type="button" id="add_row"
                                                    @if($procurement?->procurementDetails)
                                                    onclick="procurementAddRowUpdate(this)"
                                                    @else
                                                    onclick="procurementAddRow(this)"
                                                    @endif
                                                    class="btn btn-info float-end"><i class="fas fa-plus"></i>Tambah
                                                    Produk
                                                    Lain</button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group"><label for="" class="text-bold">Diskon (%)</label>
                                        <input type="number" min="0"
                                            onchange="if(document.getElementById('procurement_discount2')){document.getElementById('procurement_discount2').value=`${(this.value/100) * document.getElementById('procurement_total').value}`;calculateGrandTotal()};if(document.getElementById('procurement_discount2_update')){document.getElementById('procurement_discount2_update').value=`${(this.value/100) * document.getElementById('procurement_total_update').value}`;calculateGrandTotalUpdate()}"
                                            class="form-control" name="procurement_discount" id="procurement_discount"
                                            value="{{ $procurement?->procurement_discount ? round(($procurement?->procurement_discount/$procurement?->procurement_total)*100,2) : 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"><label for="" class="text-bold">Status
                                            Pembayaran</label>
                                        <select class="form-control" name="payment_status" id="payment_status">
                                            @foreach ($payment_status as $status)
                                            <option value="{{ $status[0] }}" @if ($procurement?->payment_status ==
                                                $status[0]) selected @endif>
                                                {{ $status[1] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"><label for="" class="text-bold">Status
                                            Pembelian</label>
                                        <select class="form-control" name="procurement_status" id="procurement_status">
                                            @foreach ($statuses as $status)
                                            <option value="{{ $status[0] }}" @if ($procurement?->procurement_status ==
                                                $status[0]) selected @endif>
                                                {{ $status[1] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"><label for="" class="text-bold">Dengan Pajak</label>
                                        @if ($procurement?->ProcurementDetails)
                                        <div class="form-check">
                                            <input class="form-check-input" onchange="calculateGrandTotalUpdate()"
                                                type="checkbox" name="procurement_tax" id="procurement_tax_check_update"
                                                value="true" value="true" @if ($procurement?->procurement_tax> 0)
                                            checked
                                            @endif>
                                            <label class="form-check-label" for="procurement_tax_check">
                                                Pajak
                                            </label>
                                        </div>
                                        @else
                                        <div class="form-check">
                                            <input class="form-check-input" onchange="calculateGrandTotal()"
                                                type="checkbox" name="procurement_tax" id="procurement_tax_check"
                                                value="true" checked>
                                            <label class="form-check-label" for="procurement_tax_check">
                                                Pajak
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group"><label for="" class="text-bold">Export Audit</label>
                                        <div class="form-check">
                                            <input class="form-check-input" onchange="" type="checkbox"
                                                name="procurement_audit" id="procurement_audit_check" value="true"
                                                @if($procurement?->procurement_audit == 'true')
                                            checked
                                            @endif>
                                            <label class="form-check-label" for="procurement_audit_check">
                                                Audit
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group"><label for="" class="text-bold">Catatan
                                            Pesanan</label>
                                        <textarea class="form-control" name="procurement_notes" id="procurement_notes"
                                            cols="30" rows="2">{{ $procurement?->procurement_notes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if ($procurement?->ProcurementDetails)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="">Total :</span>
                                <div class=" float-end">
                                    <input class="form-control-plaintext form-control-sm text-end " readonly
                                        type="number" name="" id="procurement_total_update" value="{{
                                        intval($procurement?->procurement_total) ??
                                        0}}" autocomplete="off">
                                    <div class="inline">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Diskon (%) :</span>
                                <div class="float-end">
                                    <input class="form-control-plaintext form-control-sm text-danger text-end" readonly
                                        type="number" name="" id="procurement_discount2_update" value="{{
                                        intval($procurement?->procurement_discount) ??
                                        0}}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>PPN (11%) :</span>
                                <div class="float-end">
                                    <input class="form-control-plaintext form-control-sm text-end" readonly
                                        type="number" name="" id="procurement_tax_update" value="{{
                                        intval($procurement?->procurement_tax) ??
                                        0}}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Grand Total :</span>
                                <div class="float-end">
                                    <input class="form-control-plaintext form-control-sm text-end" readonly
                                        type="number" name="" id="procurement_grand_total_update" value="{{
                                        intval($procurement?->procurement_grand_total) ??
                                        0}}">
                                </div>
                            </div>
                            @else
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="">Total :</span>
                                <div class=" float-end">
                                    <input class="form-control-plaintext form-control-sm text-end " readonly
                                        type="number" name="" id="procurement_total" value="{{
                                        intval($procurement?->procurement_total) ??
                                        0}}" autocomplete="off">
                                    <div class="inline">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Diskon (%) :</span>
                                <div class="float-end">
                                    <input class="form-control-plaintext form-control-sm text-danger text-end" readonly
                                        type="number" name="" id="procurement_discount2" value="{{
                                        intval($procurement?->procurement_discount) ??
                                        0}}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>PPN (11%) :</span>
                                <div class="float-end">
                                    <input class="form-control-plaintext form-control-sm text-end" readonly
                                        type="number" name="" id="procurement_tax" value="{{
                                        intval($procurement?->procurement_tax) ??
                                        0}}">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Grand Total :</span>
                                <div class="float-end">
                                    <input class="form-control-plaintext form-control-sm text-end" readonly
                                        type="number" name="" id="procurement_grand_total" value="{{
                                        intval($procurement?->procurement_grand_total) ??
                                        0}}">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    @if ($route === 'procurement.create' || auth()->user()->user_type==='OWNER')
                    @csrf
                    <button type="submit" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Simpan</span>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@push('script')
<script defer>
    let procurementMaxRow = parseInt("{!! $products->count() !!}")
    const current_tax = 0.11;
    const procurement_total_label = document.getElementById('procurement_total');
    const procurement_grand_total_label = document.getElementById('procurement_grand_total');
    const procurement_tax_check = document.getElementById('procurement_tax_check');
    const procurement_discount_label = document.getElementById('procurement_discount2');
    const procurement_tax_label = document.getElementById('procurement_tax');

    function setPrice(elem) {
        if(document.querySelector(`[id='product_price'][index='${elem.attributes.index.value}']`)){
            document.querySelector(`[id='product_price'][index='${elem.attributes.index.value}']`).value=elem.options[elem.selectedIndex].attributes.price.value
        }
        if(document.querySelector(`[id='product_price_update'][index='${elem.attributes.index.value}']`)){
            document.querySelector(`[id='product_price_update'][index='${elem.attributes.index.value}']`).value=elem.options[elem.selectedIndex].attributes.price.value
        }
    }

    function calculateGrandTotal(){
        let grand_total = 0, total = 0, discount = 0, tax = 0;
        total = procurement_total_label.value;
        discount = procurement_discount_label.value;
        grand_total = total - discount
        if(procurement_tax_check.checked){
            tax = procurement_tax_label.value;
            tax = ~~((total - discount) * current_tax)
            grand_total = ~~((total - discount) * (1 + current_tax))
        }
        procurement_tax_label.value = tax;
        procurement_grand_total_label.value= grand_total;
    }

    function calculateGrandTotalUpdate(){
        let procurement_tax_check_update = document.getElementById('procurement_tax_check_update');
        let procurement_total_label_update = document.getElementById('procurement_total_update');
        let procurement_discount_label_update = document.getElementById('procurement_discount2_update');
        let procurement_tax_label_update = document.getElementById('procurement_tax_update');
        let procurement_grand_total_label_update = document.getElementById('procurement_grand_total_update');
        let grand_total = 0, total = 0, discount = 0, tax = 0;
        total = procurement_total_label_update.value;
        discount = procurement_discount_label_update.value;
        grand_total = total - discount
        console.log({procurement_tax_check_update,procurement_total_label_update,procurement_discount_label_update,procurement_tax_label_update,procurement_grand_total_label_update})
        if(procurement_tax_check_update.checked){
            tax = procurement_tax_label_update.value;
            tax = ~~((total - discount) * current_tax)
            grand_total = ~~((total - discount) * (1 + current_tax))
        }
        procurement_tax_label_update.value = tax;
        procurement_grand_total_label_update.value= grand_total;
    }

    function procurementAddRow(elem) {
        let index  = $('#dynamic-procurement-product tr').length;
        if(index<procurementMaxRow){
            $('#dynamic-procurement-product').append(`<tr id="row${index}"> <td> <select class="form-control product-select2" name="product_id[]" index="${index}" id="product_id" onchange="setPrice(this)"> @if ($products) <option value="">Pilih Produk</option> @foreach ($products as $product) <option value="{{ $product->product_id }}" price="{{ $product->product_buy_price }}"> {{ $product->product_name }} ({{ $product->product_quantity }}) </option> @endforeach @endif </select> </td> <td> <input type="number" name="product_quantity[]" onchange="calculate()" id="product_quantity" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_price[]" onchange="calculate()" step="100" id="product_price" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_subtotal[]" step="100" id="product_subtotal" index="${index}" min="0" class="form-control" placeholder="" value="0" readonly> </td> <td> <div class="d-flex"> <button type="button" onclick="removeRow(${index})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button> </div> </td> </tr>`);
            index++;
            if(index==procurementMaxRow){
                elem.disabled=true
            }
        }
    }

    function removeRow(elem){
        document.querySelector(`[id="row${elem}"]`).remove();
        if(index < procurementMaxRow){
                document.getElementById("add_row").disabled = false
        }
    }

    function calculate() {
        let index  = $('#dynamic-procurement-product tr').length;
        let count = 0
        let product_quantity =0 , product_price =0 , product_subtotal =0 ,procurement_total =0 ,procurement_grand_total = 0
        let procurement_discount = document.getElementById('procurement_discount').value
        while (count != index) {
            product_quantity = document.querySelector(`[id="product_quantity"][index="${count}"]`) && document.querySelector(`[id="product_quantity"][index="${count}"]`).value ;
            product_price = document.querySelector(`[id="product_price"][index="${count}"]`) && document.querySelector(`[id="product_price"][index="${count}"]`).value;
            product_subtotal = product_quantity * product_price
            if(document.querySelector(`[id="product_subtotal"][index="${count}"]`)){
                document.querySelector(`[id="product_subtotal"][index="${count}"]`).value = product_subtotal
            }
            procurement_total = procurement_total + product_subtotal;
            count++
        }
        procurement_total_label.value = procurement_total;
        calculateGrandTotal()
    }

    function procurementAddRowUpdate(elem) {
        let index  = $('#procurement-product tr').length;
        if(index==procurementMaxRow){
            elem.disabled=true
        }
        if(index<procurementMaxRow){
            $('#procurement-product').append(`<tr id="row${index}"> <td> <select class="form-control product-select2" name="product_id[]" index="${index}" id="product_id" onchange="setPrice(this)"> @if ($products) <option value="">Pilih Produk</option> @foreach ($products as $product) <option value="{{ $product->product_id }}" price="{{ $product->product_buy_price }}"> {{ $product->product_name }} ({{ $product->product_quantity }}) </option> @endforeach @endif </select> </td> <td> <input type="number" name="product_quantity[]" onchange="calculateUpdate()" id="product_quantity_update" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_price[]" onchange="calculateUpdate()" step="100" id="product_price_update" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_subtotal[]" step="100" id="product_subtotal_update" index="${index}" min="0" class="form-control" placeholder="" value="0" readonly> </td> <td> <div class="d-flex"> <button type="button" onclick="removeRow(${index})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button> </div> </td> </tr>`);
            index++;
        }
    }

    function calculateUpdate() {
        let procurement_total_label_update = document.getElementById('procurement_total_update');
        let index  = $('#procurement-product tr').length;
        let count = 0
        let product_quantity = 0 , product_price = 0 , product_subtotal = 0 ,procurement_total = 0 ,procurement_grand_total = 0
        let procurement_discount = document.getElementById('procurement_discount').value

        while (count != index) {
            product_quantity = document.querySelector(`[id="product_quantity_update"][index="${count}"]`) && parseInt(document.querySelector(`[id="product_quantity_update"][index="${count}"]`).value);
            product_price = document.querySelector(`[id="product_price_update"][index="${count}"]`) && parseInt(document.querySelector(`[id="product_price_update"][index="${count}"]`).value);
            product_subtotal = product_quantity * product_price
                if(document.querySelector(`[id="product_subtotal_update"][index="${count}"]`)){
                    document.querySelector(`[id="product_subtotal_update"][index="${count}"]`).value = product_subtotal
                }
            procurement_total = procurement_total + product_subtotal;
            count++
        }
        procurement_total_label_update.value = procurement_total;
        calculateGrandTotalUpdate()
    }
</script>
@endpush
