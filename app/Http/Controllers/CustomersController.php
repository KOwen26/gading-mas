<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Customers;
use App\Models\Transactions;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Customers $customers)
    {
        //
        return view('pages.customer', ['customers' => $customers::orderBy('customer_name', 'asc')->get()]);
    }

    public function transaction($id, Transactions $transactions, Customers $customers)
    {
        $customer_transaction = $transactions::where('customer_id', $id)->get();
        return view('components.customers.customer-transaction', ['customer_transaction' => $customer_transaction, 'customer' => $customers::find($id)]);
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
        $customer = new Customers;
        $customer->customer_name = $request->customer_name;
        $customer->city_id = $request->city_id;
        $customer->customer_phone = $request->customer_phone ?? null;
        $customer->customer_email = $request->customer_email ?? null;
        $customer->customer_address = $request->customer_address ?? null;
        $customer->customer_notes = $request->customer_notes ?? null;
        $customer->save();

        // Logging
        $action = array();
        $action['action_name'] = "insert";
        $action['action_table'] = "customers";
        $action['action_description'] = "Menambahkan Pelanggan Baru";
        $action['action_payload'] = $customer->toJson();
        ActionsController::store($action);

        return redirect('customer')->with('success', 'Data Pelanggan Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function show(Customers $customers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Customers $customers, Cities $cities)
    {
        //
        $route = "customer.update";
        return view('components.customers.customer-modal', ['route' => $route, 'customer' => $customers::find($request->id), 'cities' => $cities::select('city_id', 'city_name')->orderBy('city_name', 'asc')->get()->toArray()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request, Customers $customers)
    {
        //
        $customer = $customers::find($id);
        $customer->customer_name = $request->customer_name;
        $customer->city_id = $request->city_id;
        $customer->customer_phone = $request->customer_phone;
        $customer->customer_email = $request->customer_email;
        $customer->customer_address = $request->customer_address;
        $customer->customer_notes = $request->customer_notes;
        $customer->save();

        // Logging
        $action = array();
        $action['action_name'] = "update";
        $action['action_table'] = "customers";
        $action['action_description'] = "Memperbarui Pelanggan";
        $action['action_payload'] = $customer->toJson();
        ActionsController::store($action);

        return redirect('customer')->with('success', 'Data Pelanggan Berhasil Diperbarui');
    }

    public function delete(Request $request)
    {
        $route = "customer.destroy";
        return view('components.delete-modal', ['id' => $request->id, 'name' => $request->name, 'route' => $route]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customers  $customers
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Customers $customers)
    {
        // Logging
        $action = array();
        $action['action_name'] = "delete";
        $action['action_table'] = "customers";
        $action['action_description'] = "Menghapus Pelanggan";
        $action['action_payload'] = $customers::find($id)->toJson();
        ActionsController::store($action);

        $customers::find($id)->delete();
        return redirect('customer')->with('success', 'Data Berhasil Dihapus');
    }
}