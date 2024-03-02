@php
    if ($route == 'transaction.update') {
        $id = ['id' => $transaction->transaction_id];
    }
    $statuses = [['NEW', 'Baru'], ['PROCESSING', 'Diproses'], ['FINISHED', 'Selesai']];
    $payment_status = [['PAID', 'Sudah Lunas'], ['INSTALLMENT', 'Dicicil'], ['UNPAID', 'Belum Dibayar']];
@endphp

<form method="POST" action="{{ route($route, $id) }}">
    @if ($route == 'transaction.update')
        @method('put')
    @endif
    <div class="modal fade text-left w-100" id="transaction-detail" role="dialog" aria-labelledby="transaction-modal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="transaction-modal">
                        @if ($route != 'transaction.update')
                            Tambah Penjualan Baru
                        @else
                            Perbarui Data Penjualan
                        @endif
                    </h4>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                        <i style="font-size:1.25rem" class="fas fa-times w-5 "></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Kode Penjualan</label>
                                <input type="text" readonly class="form-control" placeholder="" name="transaction_id"
                                    value="{{ $transaction?->transaction_id ?? $id }}">
                                <input type="hidden" id="route" value="{{ $route }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Milik Perusahaan</label>
                                <select class="form-control" name="company_id" id="company_id">
                                    @if ($companies)
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->company_id }}"
                                                @if ($transaction?->company_id == $company->company_id) selected @endif>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Tanggal Penjualan</label>
                                <input type="datetime-local" class="form-control" placeholder="" name="transaction_date"
                                    id="transaction_date"
                                    value="{{ $transaction?->transaction_date ? date('Y-m-d\TH:i:s', strtotime($transaction?->transaction_date)) : date('Y-m-d\TH:i:s') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group"><label for="" class="text-bold">Jatuh Tempo
                                    Pembayaran</label>
                                <input type="date" class="form-control" placeholder="" name="transaction_due_date"
                                    id="transaction_due_date"
                                    value="{{ $transaction?->transaction_due_date ? date('Y-m-d', strtotime($transaction?->transaction_due_date)) : date('Y-m-d') }}">
                            </div>
                            <div class="form-group"><label for="" class="text-bold">Nama Pelanggan</label>
                                <select class="form-control" name="customer_id" id="customer_id" required>
                                    <option value="">Pilih Pelanggan</option>
                                    @if ($customers)
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->customer_id }}"
                                                @if ($transaction?->customer_id == $customer->customer_id) selected @endif>
                                                {{ $customer->customer_name }}
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
                                <table class="table" id="transaction_details">
                                    <thead class="">
                                        <tr>
                                            <th style="min-width: 400px">Nama Produk</th>
                                            <th>Jumlah Produk</th>
                                            <th>Harga Produk</th>
                                            <th>Subtotal Produk</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    @if ($transaction?->TransactionDetails)
                                        <tbody id="transaction-product">
                                            @foreach ($transaction?->TransactionDetails as $transaction_detail)
                                                <tr id="row{{ $loop->iteration - 1 }}">
                                                    <td>
                                                        <select class="form-control product-select2" name="product_id[]"
                                                            index="{{ $loop->iteration - 1 }}" id="product_id"
                                                            onchange="setPrice(this)">
                                                            @if ($products)
                                                                <option value="">Pilih Produk</option>
                                                                @foreach ($products as $product)
                                                                    <option value="{{ $product->product_id }}"
                                                                        @if ($transaction_detail?->product_id == $product->product_id) selected @endif
                                                                        price="{{ $product->product_sell_price }}">
                                                                        {{ $product->product_name }}
                                                                        ({{ $product->product_quantity }})
                                                                    </option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="product_quantity[]"
                                                            onchange="calculateUpdate()" id="product_quantity_update"
                                                            index="{{ $loop->iteration - 1 }}" min="0"
                                                            max="" class="form-control" placeholder=""
                                                            value="{{ $transaction_detail?->product_quantity ?? 0 }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="product_price[]"
                                                            onchange="calculateUpdate()" id="product_price_update"
                                                            index="{{ $loop->iteration - 1 }}" min="0"
                                                            class="form-control" placeholder=""
                                                            value="{{ $transaction_detail?->product_price ?? 0 }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="product_subtotal[]"
                                                            id="product_subtotal_update"
                                                            index="{{ $loop->iteration - 1 }}" min="0"
                                                            class="form-control" placeholder=""
                                                            value="{{ $transaction_detail?->product_quantity * $transaction_detail?->product_price ?? 0 }}"
                                                            readonly>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            @if ($loop->iteration > 1)
                                                                <button type="button"
                                                                    onclick="removeRow({{ $loop->iteration - 1 }})"
                                                                    index="{{ $loop->iteration - 1 }}"
                                                                    class="btn btn-danger"><i
                                                                        class="fas fa-trash-alt"></i></button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    @else
                                        <tbody id="dynamic-transaction-product">
                                            <tr id="row0">
                                                <td>
                                                    <select class="form-control product-select2" name="product_id[]"
                                                        index="0" id="product_id" onchange="setPrice(this)"
                                                        required>
                                                        @if ($products)
                                                            <option value="">Pilih Produk</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->product_id }}"
                                                                    price="{{ $product->product_sell_price }}">
                                                                    {{ $product->product_name }}
                                                                    ({{ $product->product_quantity }})
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" name="product_quantity[]"
                                                        onchange="calculate()" id="product_quantity" index="0"
                                                        min="0" class="form-control" placeholder=""
                                                        value="0">
                                                </td>
                                                <td>
                                                    <input type="number" name="product_price[]"
                                                        onchange="calculate()" id="product_price" index="0"
                                                        min="0" class="form-control" placeholder=""
                                                        value="0">
                                                </td>
                                                <td>
                                                    <input type="number" name="product_subtotal[]"
                                                        id="product_subtotal" index="0" min="0"
                                                        class="form-control" placeholder="" value="0" readonly>
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
                                                    @if ($transaction?->TransactionDetails) onclick="transactionAddRowUpdate(this)"
                                                    @else
                                                    onclick="transactionAddRow(this)" @endif
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
                                    <div class="form-group"><label for="" class="text-bold">Diskon
                                            (%)</label>
                                        <input type="number" min="0"
                                            onchange="
                                        if(document.getElementById('transaction_discount2')){document.getElementById('transaction_discount2').value=`${(this.value/100) * document.getElementById('transaction_total').value}`;calculateGrandTotal()};if(document.getElementById('transaction_discount2_update')){document.getElementById('transaction_discount2_update').value=`${(this.value/100) * document.getElementById('transaction_total_update').value}`;calculateGrandTotalUpdate()}
                                            "
                                            class="form-control" name="transaction_discount"
                                            id="transaction_discount"
                                            value="{{ $transaction?->transaction_discount && (int) $transaction?->transaction_total > 0
                                                ? round(($transaction?->transaction_discount / $transaction?->transaction_total) * 100, 2)
                                                : 0 }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"><label for="" class="text-bold">Status
                                            Pembayaran</label>
                                        <select class="form-control" name="payment_status" id="payment_status">
                                            @foreach ($payment_status as $status)
                                                <option value="{{ $status[0] }}"
                                                    @if ($transaction?->payment_status == $status[0]) selected @endif>
                                                    {{ $status[1] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"><label for="" class="text-bold">Status
                                            Penjualan</label>
                                        <select class="form-control" name="transaction_status"
                                            id="transaction_status">
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status[0] }}"
                                                    @if ($transaction?->transaction_status == $status[0]) selected @endif>
                                                    {{ $status[1] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group"><label for="" class="text-bold">Dengan
                                            Pajak</label>
                                        @if ($transaction?->TransactionDetails)
                                            <div class="form-check">
                                                <input class="form-check-input" onchange="calculateGrandTotalUpdate()"
                                                    type="checkbox" name="transaction_tax"
                                                    id="transaction_tax_check_update" value="true"
                                                    @if ($transaction?->transaction_tax > 0) checked @endif>
                                                <label class="form-check-label" for="transaction_tax_check">
                                                    Pajak
                                                </label>
                                            </div>
                                        @else
                                            <div class="form-check">
                                                <input class="form-check-input" onchange="calculateGrandTotal()"
                                                    type="checkbox" name="transaction_tax" id="transaction_tax_check"
                                                    value="true" checked>
                                                <label class="form-check-label" for="transaction_tax_check">
                                                    Pajak
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="form-group"><label for="" class="text-bold">Export
                                            Audit</label>
                                        <div class="form-check">
                                            <input class="form-check-input" onchange="" type="checkbox"
                                                name="transaction_audit" id="transaction_audit_check" value="true"
                                                @if ($transaction?->transaction_audit == 'true') checked @endif>
                                            <label class="form-check-label" for="transaction_audit_check">
                                                Audit
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group"><label for="" class="text-bold">Catatan
                                            Pesanan</label>
                                        <textarea class="form-control" name="transaction_notes" id="transaction_notes" cols="30" rows="2">{{ $transaction?->transaction_notes }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if ($transaction?->TransactionDetails)
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="">Total :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-end" readonly
                                            type="number" name="" id="transaction_total_update"
                                            value="{{ intval($transaction?->transaction_total) }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Diskon :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-danger text-end"
                                            readonly type="number" name="" id="transaction_discount2_update"
                                            value="{{ intval($transaction?->transaction_discount) ?? 0 }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>PPN (11%) :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-end" readonly
                                            type="number" name="" id="transaction_tax_update"
                                            value="{{ intval($transaction?->transaction_tax) ?? 0 }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Grand Total :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-end" readonly
                                            type="number" name="" id="transaction_grand_total_update"
                                            value="{{ intval($transaction?->transaction_grand_total) ?? 0 }}">
                                    </div>
                                </div>
                            @else
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="">Total :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-end" readonly
                                            type="number" name="" id="transaction_total"
                                            value="{{ intval($transaction?->transaction_total) ?? 0 }}"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Diskon :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-danger text-end"
                                            readonly type="number" name="" id="transaction_discount2"
                                            value="{{ intval($transaction?->transaction_discount) ?? 0 }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>PPN (11%) :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-end" readonly
                                            type="number" name="" id="transaction_tax"
                                            value="{{ intval($transaction?->transaction_tax) ?? 0 }}">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Grand Total :</span>
                                    <div class="float-end">
                                        <input class="form-control-plaintext form-control-sm text-end" readonly
                                            type="number" name="" id="transaction_grand_total"
                                            value="{{ intval($transaction?->transaction_grand_total) ?? 0 }}">
                                    </div>
                                </div>
                            @endif
                            {{-- <div class="d-flex justify-content-between align-items-center">
                                <div class="form-group"><label for="" class="text-bold">Export Audit</label>
                                    <div class="form-check">
                                        <input class="form-check-input" onchange="" type="checkbox"
                                            name="transaction_audit" id="transaction_audit_check" value="true">
                                        <label class="form-check-label" for="transaction_audit_check">
                                            Audit
                                        </label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Tutup</span>
                    </button>
                    @if ($route === 'transaction.create' || auth()->user()->user_type === 'OWNER')
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
        let transactionMaxRow = parseInt("{!! $products->count() !!}")
        const current_tax = 0.11;
        const transaction_tax_check = document.getElementById('transaction_tax_check');
        const transaction_total_label = document.getElementById('transaction_total');
        const transaction_discount_label = document.getElementById('transaction_discount2');
        const transaction_tax_label = document.getElementById('transaction_tax');
        const transaction_grand_total_label = document.getElementById('transaction_grand_total');

        function setPrice(elem) {
            if (document.querySelector(`[id='product_price'][index='${elem.attributes.index.value}']`)) {
                document.querySelector(`[id='product_price'][index='${elem.attributes.index.value}']`).value = elem.options[
                    elem.selectedIndex].attributes.price.value
            }
            if (document.querySelector(`[id='product_price_update'][index='${elem.attributes.index.value}']`)) {
                document.querySelector(`[id='product_price_update'][index='${elem.attributes.index.value}']`).value = elem
                    .options[elem.selectedIndex].attributes.price.value
            }
        }

        function calculateGrandTotal() {
            let grand_total = 0,
                total = 0,
                discount = 0,
                tax = 0;
            total = transaction_total_label.value;
            discount = transaction_discount_label.value;
            grand_total = total - discount
            if (transaction_tax_check.checked) {
                tax = transaction_tax_label.value;
                tax = ~~((total - discount) * current_tax)
                grand_total = ~~((total - discount) * (1 + current_tax))
            }
            transaction_tax_label.value = tax;
            transaction_grand_total_label.value = grand_total;
        }

        function calculateGrandTotalUpdate() {
            let transaction_tax_check_update = document.getElementById('transaction_tax_check_update');
            let transaction_total_label_update = document.getElementById('transaction_total_update');
            let transaction_discount_label_update = document.getElementById('transaction_discount2_update');
            let transaction_tax_label_update = document.getElementById('transaction_tax_update');
            let transaction_grand_total_label_update = document.getElementById('transaction_grand_total_update');
            let grand_total = 0,
                total = 0,
                discount = 0,
                tax = 0;
            total = transaction_total_label_update.value;
            discount = transaction_discount_label_update.value;
            grand_total = total - discount
            // console.log({
            //     transaction_tax_check_update,
            //     transaction_total_label_update,
            //     transaction_discount_label_update,
            //     transaction_tax_label_update,
            //     transaction_grand_total_label_update
            // })
            if (transaction_tax_check_update.checked) {
                tax = transaction_tax_label_update.value;
                tax = ~~((total - discount) * current_tax)
                grand_total = ~~((total - discount) * (1 + current_tax))
            }
            transaction_tax_label_update.value = tax;
            transaction_grand_total_label_update.value = grand_total;
        }

        function transactionAddRow(elem) {
            let index = $('#dynamic-transaction-product tr').length;
            if (index < transactionMaxRow) {
                $('#dynamic-transaction-product').append(
                    `<tr id="row${index}"> <td> <select class="form-control product-select2" name="product_id[]" index="${index}" id="product_id" onchange="setPrice(this)"> @if ($products) <option value="">Pilih Produk</option> @foreach ($products as $product) <option value="{{ $product->product_id }}" price="{{ $product->product_sell_price }}"> {{ $product->product_name }} ({{ $product->product_quantity }}) </option> @endforeach @endif </select> </td> <td> <input type="number" name="product_quantity[]" onchange="calculate()" id="product_quantity" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_price[]" onchange="calculate()"  id="product_price" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_subtotal[]"  id="product_subtotal" index="${index}" min="0" class="form-control" placeholder="" value="0" readonly> </td> <td> <div class="d-flex"> <button type="button" onclick="removeRow(${index})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button> </div> </td> </tr>`
                );
                index++;
                if (index == transactionMaxRow) {
                    elem.disabled = true
                }
            }
        }

        function removeRow(elem) {
            document.querySelector(`[id="row${elem}"]`).remove();
            if (index < transactionMaxRow) {
                document.getElementById("add_row").disabled = false
            }
        }

        function calculate() {
            let index = $('#dynamic-transaction-product tr').length
            let count = 0
            let product_quantity = 0,
                product_price = 0,
                product_subtotal = 0,
                transaction_total = 0,
                transaction_grand_total = 0
            let transaction_discount = parseInt(document.getElementById('transaction_discount').value)
            while (count != index) {
                product_quantity = document.querySelector(`[id="product_quantity"][index="${count}"]`) && document
                    .querySelector(`[id="product_quantity"][index="${count}"]`).value;
                product_price = document.querySelector(`[id="product_quantity"][index="${count}"]`) && document
                    .querySelector(`[id="product_price"][index="${count}"]`).value;
                product_subtotal = product_quantity * product_price
                if (document.querySelector(`[id="product_subtotal"][index="${count}"]`)) {
                    document.querySelector(`[id="product_subtotal"][index="${count}"]`).value = product_subtotal
                }
                transaction_total = transaction_total + product_subtotal;
                count++
            }
            transaction_total_label.value = transaction_total;
            calculateGrandTotal()
        }

        function transactionAddRowUpdate(elem) {
            let index = $('#transaction-product tr').length;
            if (index == transactionMaxRow) {
                elem.disabled = true
            }
            if (index < transactionMaxRow) {
                $('#transaction-product').append(
                    `<tr id="row${index}"> <td> <select class="form-control product-select2" name="product_id[]" index="${index}" id="product_id" onchange="setPrice(this)"> @if ($products) <option value="">Pilih Produk</option> @foreach ($products as $product) <option value="{{ $product->product_id }}" price="{{ $product->product_sell_price }}"> {{ $product->product_name }} ({{ $product->product_quantity }}) </option> @endforeach @endif </select> </td> <td> <input type="number" name="product_quantity[]" onchange="calculateUpdate()" id="product_quantity_update" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_price[]" onchange="calculateUpdate()"  id="product_price_update" index="${index}" min="0" class="form-control" placeholder="" value="0"> </td> <td> <input type="number" name="product_subtotal[]"  id="product_subtotal_update" index="${index}" min="0" class="form-control" placeholder="" value="0" readonly> </td> <td> <div class="d-flex"> <button type="button" onclick="removeRow(${index})" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button> </div> </td> </tr>`
                );
                index++;

            }
        }

        function calculateUpdate() {
            let transaction_total_label_update = document.getElementById('transaction_total_update');
            let index = $('#transaction-product tr').length
            let count = 0
            let product_quantity = 0,
                product_price = 0,
                product_subtotal = 0,
                transaction_total = 0,
                transaction_grand_total = 0
            let transaction_discount = document.getElementById('transaction_discount').value
            while (count != index) {
                product_quantity = document.querySelector(`[id="product_quantity_update"][index="${count}"]`) && parseInt(
                    document.querySelector(`[id="product_quantity_update"][index="${count}"]`).value);
                product_price = document.querySelector(`[id="product_quantity_update"][index="${count}"]`) && parseInt(
                    document.querySelector(`[id="product_price_update"][index="${count}"]`).value);
                product_subtotal = product_quantity * product_price
                if (document.querySelector(`[id="product_subtotal_update"][index="${count}"]`)) {
                    document.querySelector(`[id="product_subtotal_update"][index="${count}"]`).value = product_subtotal
                }
                transaction_total = transaction_total + product_subtotal;
                count++
            }
            transaction_total_label_update.value = parseInt(transaction_total)
            calculateGrandTotalUpdate()

        }
    </script>
@endpush
