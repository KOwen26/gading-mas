<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\ProcurementDetails;
use App\Models\Procurements;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Procurements $procurements)
    {
        //
        return view('pages.procurement', ['procurements' => $procurements]);
    }

    public function report()
    {
        return view('pages.procurement-report');
    }

    public function print($id, Procurements $procurements)
    {
        return view('print.purchase', ['procurement' => $procurements::find($id)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function setAuditIndex()
    {
        $year = date("y");
        $month = date("m");
        $index = "0001";
        $prefix = "PRC-GMU-$year";
        $lastId = DB::table('audit_procurements')->where('procurement_audit_id', 'like', "$prefix%")->max('procurement_audit_id');
        if ($lastId) {
            $newId = (intval(substr($lastId, -4)) + 1);
            $index = substr("000$newId", -4);
        }
        $id = "$prefix$month-$index";
        return $id;
    }

    public function setAudit($procurement_id)
    {
        $this->deleteAudit($procurement_id);
        $audit_index = $this->setAuditIndex();
        DB::select("insert into audit_procurements (procurement_id, procurement_audit_id, user_id, supplier_id, company_id, procurement_total, procurement_discount, procurement_tax, procurement_grand_total, procurement_date, procurement_due_date, procurement_notes, payment_status, procurement_status, procurement_audit, created_at, updated_at) select procurement_id, '$audit_index' as procurement_audit_id, user_id, supplier_id, company_id, procurement_total, procurement_discount, procurement_tax, procurement_grand_total, procurement_date, procurement_due_date, procurement_notes, payment_status, procurement_status, procurement_audit, created_at, updated_at from procurements where procurement_id = ?", [$procurement_id]);
        DB::select('insert into audit_procurement_details select * from procurement_details where procurement_id = ?', [$procurement_id]);
        ProductsController::setAudit();
    }

    public function deleteAudit($procurement_id)
    {
        DB::delete('delete from audit_procurements where procurement_id = ?', [$procurement_id]);
        DB::delete('delete from audit_procurement_details where procurement_id = ?', [$procurement_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Procurements $procurements, Products $products)
    {
        //
        // dd($request->all());
        $id = $procurements->ProcurementId();
        $procurement_total = 0;

        $procurement = new Procurements;
        $procurement->procurement_id = $id;
        $procurement->user_id = auth()->user()->user_id;
        $procurement->company_id = $request->company_id;
        $procurement->supplier_id = $request->supplier_id;
        $procurement->procurement_total = 0;
        $procurement->procurement_discount = 0;
        $procurement->procurement_tax = 0;
        $procurement->procurement_grand_total = 0;
        $procurement->procurement_date = $request->procurement_date;
        $procurement->procurement_due_date = $request->procurement_due_date;
        $procurement->payment_status = $request->payment_status ?? "PAID";
        $procurement->procurement_status = $request->procurement_status ?? "NEW";
        $procurement->procurement_audit =  $request->procurement_audit == "true" ? "true" : null;
        $procurement->procurement_notes = $request->procurement_notes ?? "";
        $procurement->save();

        for ($i = 0; $i < count($request->product_id); $i++) {
            $product = $products::find($request->product_id[$i]);
            $procurement_detail = new ProcurementDetails;
            $procurement_detail->procurement_id = $id;
            $procurement_detail->product_id = $request->product_id[$i];
            $procurement_detail->product_name = $product->product_name;
            $procurement_detail->product_quantity = $request->product_quantity[$i];
            $procurement_detail->product_price = $request->product_price[$i];
            $procurement_detail->save();
            $product->product_quantity = $product->product_quantity + $request->product_quantity[$i];
            $product->save();
            $procurement_total = $procurement_total + ($request->product_quantity[$i] * $request->product_price[$i]);
        }
        $procurement = $procurements::find($id);
        $procurement->procurement_total = $procurement_total;
        $procurement_discount = ($request->procurement_discount / 100) * $procurement_total;
        $procurement->procurement_discount = $procurement_discount;
        $procurement->procurement_grand_total = $procurement_total - $procurement_discount;
        if ($request->procurement_tax == 'true') {
            $procurement->procurement_tax = ($procurement_total - $procurement_discount) * 0.11;
            $procurement->procurement_grand_total = ($procurement_total - $procurement_discount) * 1.11;
        }
        $procurement->save();

        if ($request->procurement_audit == 'true') {
            $this->setAudit($procurement->procurement_id);
        } else {
            $this->deleteAudit($procurement->procurement_id);
        }

        // Logging
        $procurement_details = "";
        foreach ($procurement->ProcurementDetails()->get() as $details) {
            $procurement_details = "$procurement_details,$details";
        }
        $procurement_details = substr($procurement_details, 1);
        $procurement_details = "[$procurement_details]";
        $procurement = substr($procurement->toJson(), 0, -1);
        $procurement = $procurement . ',"procurement_details":' . $procurement_details . '}';
        $action = array();
        $action['action_name'] = "insert";
        $action['action_table'] = "procurements";
        $action['action_description'] = "Menambahkan Transaksi Baru";
        $action['action_payload'] = $procurement;
        ActionsController::store($action);

        return redirect('procurement')->with('success', 'Transaksi Berhasil Disimpan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Procurements  $procurements
     * @return \Illuminate\Http\Response
     */
    public function show(Procurements $procurements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Procurements  $procurements
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Procurements $procurements, Companies $companies, Products $products, Suppliers $suppliers)
    {
        //
        $route = "procurement.update";
        return view('components.procurements.procurement-modal', ['route' => $route, 'procurement' => $procurements::find($request->id), 'companies' => $companies::select('company_id', 'company_name')->orderBy('company_id', 'asc')->get(), 'products' => $products::where('product_status', 'ACTIVE')->orderBy('product_name', 'asc')->get(), 'suppliers' => $suppliers::orderBy('supplier_name', 'asc')->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Procurements  $procurements
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Procurements $procurements, ProcurementDetails $procurement_details, Products $products)
    {
        //Update Pembelian
        $procurement = $procurements::find($request->procurement_id);
        $procurement_total = $procurement?->procurement_total;
        $procurement->user_id = auth()->user()->user_id;
        $procurement->company_id = $request->company_id;
        $procurement->supplier_id = $request->supplier_id;
        $procurement->procurement_date = $request->procurement_date;
        $procurement->procurement_due_date = $request->procurement_due_date;
        $procurement->payment_status = $request->payment_status ?? "PAID";
        $procurement->procurement_status = $request->procurement_status ?? "NEW";
        $procurement->procurement_audit =  $request->procurement_audit == "true" ? "true" : null;
        $procurement->procurement_notes = $request->procurement_notes ?? "";

        // Check if there is any changes on ProcurementDetails
        $details = $procurement->ProcurementDetails()->get(['procurement_id', 'product_id', 'product_quantity', 'product_price'])->toArray();
        $details2 = array();
        for ($i = 0; $i < count($request->product_id); $i++) {
            array_push($details2, ['procurement_id' => $request->procurement_id, 'product_id' => $request->product_id[$i], 'product_quantity' => $request->product_quantity[$i], 'product_price' => $request->product_price[$i]]);
        }

        // return dd($request->all(), $details, $details2, $details !== $details2);
        if ($details !== $details2) {
            $procurement_total = 0;
            // Hapus Detail Transaksi
            $procurement_details2 = $procurement_details::where('procurement_id', $request->procurement_id)->get();
            foreach ($procurement_details2 as $procurement_detail) {
                $product = $products::find($procurement_detail->product_id);
                $product->product_quantity = $product->product_quantity - $procurement_detail->product_quantity;
                $product->save();
            }
            $procurement_details::where('procurement_id', $request->procurement_id)->delete();

            // Tambah Detil Transaksi Baru
            for ($i = 0; $i < count($request->product_id); $i++) {
                $product = $products::find($request->product_id[$i]);
                $procurement_detail = new ProcurementDetails;
                $procurement_detail->procurement_id = $request->procurement_id;
                $procurement_detail->product_id = $request->product_id[$i];
                $procurement_detail->product_name = $product->product_name;
                $procurement_detail->product_quantity = $request->product_quantity[$i];
                $procurement_detail->product_price = $request->product_price[$i];
                $procurement_detail->save();
                $product->product_quantity = $product->product_quantity + $request->product_quantity[$i];
                $product->save();
                $procurement_total = $procurement_total + ($request->product_quantity[$i] * $request->product_price[$i]);
            }
            $procurement->procurement_total = $procurement_total;
        }

        $procurement_discount = ($request->procurement_discount / 100) * $procurement_total;
        $procurement->procurement_discount = $procurement_discount;
        $procurement->procurement_tax = 0;
        $procurement->procurement_grand_total = $procurement_total - $procurement_discount;
        if ($request->procurement_tax == 'true') {
            $procurement->procurement_tax = ($procurement_total - $procurement_discount) * 0.11;
            $procurement->procurement_grand_total = ($procurement_total - $procurement_discount) * 1.11;
        }
        // dd($procurement_total, $procurement->procurement_total, $procurement_discount, $procurement->procurement_discount, $procurement->procurement_grand_total);
        $procurement->save();

        if ($request->procurement_audit == 'true') {
            $this->setAudit($procurement->procurement_id);
        } else {
            $this->deleteAudit($procurement->procurement_id);
        }

        // Logging
        $procurement_details = "";
        foreach ($procurement->ProcurementDetails()->get() as $details) {
            $procurement_details = "$procurement_details,$details";
        }
        $procurement_details = substr($procurement_details, 1);
        $procurement_details = "[$procurement_details]";
        $procurement = substr($procurement->toJson(), 0, -1);
        $procurement = $procurement . ',"procurement_details":' . $procurement_details . '}';
        $action = array();
        $action['action_name'] = "update";
        $action['action_table'] = "procurements";
        $action['action_description'] = "Memperbarui Transaksi";
        $action['action_payload'] = $procurement;
        ActionsController::store($action);

        return redirect('procurement')->with('success', 'Transaksi Berhasil Diperbarui');
    }

    public function delete(Request $request)
    {
        $route = "procurement.destroy";
        return view('components.delete-modal', ['id' => $request->id, 'name' => $request->name, 'route' => $route]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Procurements  $procurements
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Procurements $procurements, ProcurementDetails $procurement_details, Products $products)
    {
        // dd($id);

        // Logging
        $procurement = $procurements::find($id);
        $procurement_detail = "";
        foreach ($procurement->ProcurementDetails()->get() as $details) {
            $procurement_detail = "$procurement_detail,$details";
        }
        $procurement_detail = substr($procurement_detail, 1);
        $procurement_detail = "[$procurement_detail]";
        $procurement = substr($procurement->toJson(), 0, -1);
        $procurement = $procurement . ',"procurement_details":' . $procurement_detail . '}';
        $action = array();
        $action['action_name'] = "delete";
        $action['action_table'] = "procurements";
        $action['action_description'] = "Menghapus Transaksi";
        $action['action_payload'] = $procurement;
        ActionsController::store($action);

        $this->deleteAudit($id);
        $procurements::find($id)->delete();
        $procurement_details2 = $procurement_details::where('procurement_id', $id)->get();
        foreach ($procurement_details2 as $procurement_detail) {
            $product = $products::find($procurement_detail->product_id);
            $product->product_quantity = $product->product_quantity - $procurement_detail->product_quantity;
            $product->save();
        }
        $procurement_details::where('procurement_id', $id)->delete();
        return redirect('procurement')->with('success', 'Data Berhasil Dihapus');
    }
}