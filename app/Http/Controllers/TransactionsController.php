<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\Customers;
use App\Models\Products;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Transactions $transactions)
    {
        // return auth()->user()->user_id;
        // return $request->session()->all();

        return view('pages.transaction', ['transactions' => $transactions]);
    }

    public function report()
    {
        return view('pages.transaction-report');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): void
    {
        //
    }

    public function setAuditIndex()
    {
        $year = date("y");
        $month = date("m");
        $index = "0001";
        $prefix = "INV-GMU-$year";
        $lastId = DB::table('audit_transactions')->where('transaction_audit_id', 'like', "$prefix%")->max('transaction_audit_id');
        if ($lastId) {
            $newId = (intval(substr($lastId, -4)) + 1);
            $index = substr("000$newId", -4);
        }
        $id = "$prefix$month-$index";
        return $id;
    }

    public function setAudit($transaction_id): void
    {
        $this->deleteAudit($transaction_id);
        $audit_index = $this->setAuditIndex();
        DB::select("insert into audit_transactions (transaction_id, transaction_audit_id, user_id, customer_id, company_id, transaction_total, transaction_discount, transaction_tax, transaction_grand_total, transaction_date, transaction_due_date, transaction_notes, payment_status, transaction_status, transaction_audit, created_at, updated_at) select transaction_id, '$audit_index' as transaction_audit_id, user_id, customer_id, company_id, transaction_total, transaction_discount, transaction_tax, transaction_grand_total, transaction_date, transaction_due_date, transaction_notes, payment_status, transaction_status, transaction_audit, created_at, updated_at from transactions where transaction_id = ?", [$transaction_id]);
        DB::select('insert into audit_transaction_details select * from transaction_details where transaction_id = ?', [$transaction_id]);
        ProductsController::setAudit();
    }

    public function deleteAudit($transaction_id): void
    {
        DB::delete('delete from audit_transactions where transaction_id = ?', [$transaction_id]);
        DB::delete('delete from audit_transaction_details where transaction_id = ?', [$transaction_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Transactions $transactions, Products $products)
    {
        // dd($request->all());
        $id = $transactions->TransactionId();
        $transaction_total = 0;
        $transaction = new Transactions;
        $transaction->transaction_id = $id;
        $transaction->user_id = auth()->user()->user_id;
        $transaction->company_id = $request->company_id;
        $transaction->customer_id = $request->customer_id;
        $transaction->transaction_total = 0;
        $transaction->transaction_discount = 0;
        $transaction->transaction_tax = 0;
        $transaction->transaction_grand_total = 0;
        $transaction->transaction_date = $request->transaction_date;
        $transaction->transaction_due_date = $request->transaction_due_date;
        $transaction->payment_status = $request->payment_status ?? "PAID";
        $transaction->transaction_audit =  $request->transaction_audit == "true" ? "true" : null;
        $transaction->transaction_status = $request->transaction_status ?? "NEW";
        $transaction->transaction_notes = $request->transaction_notes ?? "";
        $transaction->save();

        for ($i = 0; $i < count($request->product_id); $i++) {
            $product = $products::find($request->product_id[$i]);
            $transaction_detail = new TransactionDetails;
            $transaction_detail->transaction_id = $id;
            $transaction_detail->product_id = $request->product_id[$i];
            $transaction_detail->product_name = $product->product_name;
            $transaction_detail->product_unit = $product->product_unit;
            $transaction_detail->product_quantity = $request->product_quantity[$i];
            $transaction_detail->product_price = $request->product_price[$i];
            $transaction_detail->save();
            $product->product_quantity = $product->product_quantity - $request->product_quantity[$i];
            $product->save();
            $transaction_total = $transaction_total + ($request->product_quantity[$i] * $request->product_price[$i]);
        }
        $transaction = $transactions::find($id);
        $transaction->transaction_total = $transaction_total;
        $transaction_discount = ($request->transaction_discount / 100) * $transaction_total;
        $transaction->transaction_discount = $transaction_discount;
        $transaction->transaction_grand_total = $transaction_total - $transaction_discount;
        if ($request->transaction_tax == 'true') {
            $transaction->transaction_tax = ($transaction_total - $transaction_discount) * 0.11;
            $transaction->transaction_grand_total = ($transaction_total - $transaction_discount) * 1.11;
        }
        $transaction->save();

        if ($request->transaction_audit == 'true') {
            $this->setAudit($transaction->transaction_id);
        } else {
            $this->deleteAudit($transaction->transaction_id);
        }

        // Logging
        $transaction_details = "";
        foreach ($transaction->TransactionDetails()->get() as $details) {
            $transaction_details = "$transaction_details,$details";
        }
        $transaction_details = substr($transaction_details, 1);
        $transaction_details = "[$transaction_details]";
        $transaction = substr($transaction->toJson(), 0, -1);
        $transaction = $transaction . ',"transaction_details":' . $transaction_details . '}';
        $action = array();
        $action['action_name'] = "insert";
        $action['action_table'] = "transactions";
        $action['action_description'] = "Menambahkan Transaksi Baru";
        $action['action_payload'] = $transaction;
        ActionsController::store($action);

        return redirect('transaction')->with('success', 'Transaksi Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show(Transactions $transactions): void
    {
        //
    }

    public function print($id, Transactions $transactions)
    {
        return view('print.invoice', ['transaction' => $transactions::find($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Transactions $transactions, Companies $companies, Products $products, Customers $customers)
    {
        //
        $route = "transaction.update";
        return view('components.transactions.transaction-modal', ['route' => $route, 'transaction' => $transactions::find($request->id), 'companies' => $companies::select('company_id', 'company_name')->orderBy('company_id', 'asc')->get(), 'products' => $products::where('product_status', 'ACTIVE')->orderBy('product_name', 'asc')->get(), 'customers' => $customers::orderBy('customer_name', 'asc')->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transactions $transactions, TransactionDetails $transaction_details, Products $products)
    {
        // return dd($request->all());
        //Update Transaksi
        $transaction = $transactions::find($request->transaction_id);
        $transaction_total = $transaction?->transaction_total;
        $transaction->user_id = auth()->user()->user_id;
        $transaction->company_id = $request->company_id;
        $transaction->customer_id = $request->customer_id;
        $transaction->transaction_date = $request->transaction_date;
        $transaction->transaction_due_date = $request->transaction_due_date;
        $transaction->payment_status = $request->payment_status ?? "PAID";
        $transaction->transaction_status = $request->transaction_status ?? "NEW";
        $transaction->transaction_audit =  $request->transaction_audit == "true" ? "true" : null;
        $transaction->transaction_notes = $request->transaction_notes ?? "";

        // Check if there is any changes on TransactionDetails
        $details = $transaction->TransactionDetails()->get(['transaction_id', 'product_id', 'product_quantity', 'product_price'])->toArray();
        $details2 = array();
        for ($i = 0; $i < count($request->product_id); $i++) {
            array_push($details2, ['transaction_id' => $request->transaction_id, 'product_id' => $request->product_id[$i], 'product_quantity' => $request->product_quantity[$i], 'product_price' => $request->product_price[$i]]);
        }

        // return dd($request->all(), $details, $details2, $details !== $details2);
        if ($details !== $details2) {
            $transaction_total = 0;
            // Hapus Detail Transaksi
            $transaction_details2 = $transaction_details::where('transaction_id', $request->transaction_id)->get();
            foreach ($transaction_details2 as $transaction_detail) {
                $product = $products::find($transaction_detail->product_id);
                $product->product_quantity = $product->product_quantity + $transaction_detail->product_quantity;
                $product->save();
            }
            $transaction_details::where('transaction_id', $request->transaction_id)->delete();

            // Tambah Detil Transaksi Baru
            for ($i = 0; $i < count($request->product_id); $i++) {
                $product = $products::find($request->product_id[$i]);
                $transaction_detail = new TransactionDetails;
                $transaction_detail->transaction_id = $request->transaction_id;
                $transaction_detail->product_id = $request->product_id[$i];
                $transaction_detail->product_name = $product->product_name;
                $transaction_detail->product_unit = $product->product_unit;
                $transaction_detail->product_quantity = $request->product_quantity[$i];
                $transaction_detail->product_price = $request->product_price[$i];
                $transaction_detail->save();
                $product->product_quantity = $product->product_quantity - $request->product_quantity[$i];
                $product->save();
                $transaction_total = $transaction_total + ($request->product_quantity[$i] * $request->product_price[$i]);
            }
            $transaction->transaction_total = $transaction_total;
        }
        $transaction_discount = ($request->transaction_discount / 100) * $transaction_total;
        $transaction->transaction_discount = $transaction_discount;
        if ($request->transaction_tax == 'true') {
            $transaction->transaction_tax = ($transaction_total - $transaction_discount) * 0.11;
            $transaction->transaction_grand_total = ($transaction_total - $transaction_discount) * 1.11;
        } else {
            $transaction->transaction_tax = 0;
            $transaction->transaction_grand_total = $transaction_total - $transaction_discount;
        }
        // dd($transaction_total, $transaction_discount, $transaction->transaction_grand_total);
        $transaction->save();

        if ($request->transaction_audit == 'true') {
            $this->setAudit($transaction->transaction_id);
        } else {
            $this->deleteAudit($transaction->transaction_id);
        }

        // Logging
        $transaction_details = "";
        foreach ($transaction->TransactionDetails()->get() as $details) {
            $transaction_details = "$transaction_details,$details";
        }
        $transaction_details = substr($transaction_details, 1);
        $transaction_details = "[$transaction_details]";
        $transaction = substr($transaction->toJson(), 0, -1);
        $transaction = $transaction . ',"transaction_details":' . $transaction_details . '}';
        $action = array();
        $action['action_name'] = "update";
        $action['action_table'] = "transactions";
        $action['action_description'] = "Memperbarui Transaksi";
        $action['action_payload'] = $transaction;
        ActionsController::store($action);


        return redirect('transaction')->with('success', 'Transaksi Berhasil Diperbarui');
    }

    public function delete(Request $request)
    {
        $route = "transaction.destroy";
        return view('components.delete-modal', ['id' => $request->id, 'name' => $request->name, 'route' => $route]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Transactions $transactions, TransactionDetails $transaction_details, Products $products)
    {
        //
        // dd($id);

        // Logging
        $transaction = $transactions::find($id);
        $transaction_detail = "";
        foreach ($transaction?->TransactionDetails()?->get() as $details) {
            $transaction_detail = "$transaction_detail,$details";
        }
        $transaction_detail = substr($transaction_detail, 1);
        $transaction_detail = "[$transaction_detail]";
        $transaction = substr($transaction->toJson(), 0, -1);
        $transaction = $transaction . ',"transaction_details":' . $transaction_detail . '}';
        $action = array();
        $action['action_name'] = "delete";
        $action['action_table'] = "transactions";
        $action['action_description'] = "Menghapus Transaksi";
        $action['action_payload'] = $transaction;
        ActionsController::store($action);

        // Delete
        $this->deleteAudit($id);
        $transactions::find($id)->delete();
        $transaction_details2 = $transaction_details::where('transaction_id', $id)->get();
        foreach ($transaction_details2 as $transaction_detail) {
            $product = $products::find($transaction_detail->product_id);
            $product->product_quantity = $product->product_quantity + $transaction_detail->product_quantity;
            $product->save();
        }
        $transaction_details::where('transaction_id', $id)->delete();
        return redirect('transaction')->with('success', 'Data Berhasil Dihapus');
    }
}
