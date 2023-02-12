<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Procurements;
use App\Models\Suppliers;
use App\Models\Transactions;
use Illuminate\Http\Request;

class SuppliersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Suppliers $suppliers, Cities $cities)
    {
        return view('pages.supplier', ['suppliers' => $suppliers::orderBy('supplier_name', 'asc')->get(), 'cities' => $cities::orderBy('city_name', 'asc')->get()]);
    }

    public function transaction($id, Procurements $procurements, Suppliers $suppliers)
    {
        $supplier_transaction = $procurements::where('supplier_id', $id)->get();
        return view('components.suppliers.supplier-transaction', ['supplier_transaction' => $supplier_transaction, 'supplier' => $suppliers::find($id)]);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $supplier = new Suppliers;
        $supplier->supplier_name = $request->supplier_name ?? null;
        $supplier->supplier_phone = $request->supplier_phone ?? null;
        $supplier->supplier_email = $request->supplier_email ?? null;
        $supplier->supplier_address = $request->supplier_address ?? null;
        $supplier->supplier_contact_name = $request->supplier_contact_name ?? null;
        $supplier->supplier_notes = $request->supplier_notes ?? null;
        $supplier->city_id = $request->city_id ?? null;
        $supplier->save();

        // Logging
        $action = array();
        $action['action_name'] = "create";
        $action['action_table'] = "suppliers";
        $action['action_description'] = "Memperbarui Pelanggan";
        $action['action_payload'] = $supplier->toJson();
        ActionsController::store($action);

        return redirect('supplier')->with('success', 'Data Supplier Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function show(Suppliers $suppliers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Suppliers $suppliers, Cities $cities)
    {
        // dd($cities::select('city_id', 'city_name')->get()->toArray());
        $route = "supplier.update";
        return view('components.suppliers.supplier-modal', ['route' => $route, 'supplier' => $suppliers::find($request->id), 'cities' => $cities::select('city_id', 'city_name')->orderBy('city_name', 'asc')->get()->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        //
        // dd($id, $request);
        $supplier = Suppliers::find($id);
        $supplier->supplier_name = $request->supplier_name;
        $supplier->supplier_phone = $request->supplier_phone;
        $supplier->supplier_email = $request->supplier_email;
        $supplier->supplier_address = $request->supplier_address;
        $supplier->supplier_contact_name = $request->supplier_contact_name;
        $supplier->supplier_notes = $request->supplier_notes;
        $supplier->city_id = $request->city_id;
        $supplier->save();

        // Logging
        $action = array();
        $action['action_name'] = "update";
        $action['action_table'] = "suppliers";
        $action['action_description'] = "Memperbarui Pelanggan";
        $action['action_payload'] = $supplier->toJson();
        ActionsController::store($action);

        return redirect('supplier')->with('success', 'Data Supplier Berhasil Diperbarui');
    }

    public function delete(Request $request)
    {
        // dd($request);
        $route = "supplier.destroy";
        return view('components.delete-modal', ['id' => $request->id, 'name' => $request->name, 'route' => $route]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Suppliers  $suppliers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Suppliers $suppliers)
    {
        // Logging
        $action = array();
        $action['action_name'] = "update";
        $action['action_table'] = "suppliers";
        $action['action_description'] = "Memperbarui Pelanggan";
        $action['action_payload'] = $suppliers::find($id)->toJson();
        ActionsController::store($action);

        $suppliers::find($id)->delete();
        return redirect('supplier')->with('success', 'Data Berhasil Dihapus');
    }
}