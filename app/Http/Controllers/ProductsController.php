<?php

namespace App\Http\Controllers;

use App\Models\Companies;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.product');
    }

    public function list(Products $products)
    {
        return $products::orderBy('product_name', 'asc')->get();
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

    public static function setAudit(): void
    {
        $product = DB::table('products')->count();
        $audit_product = DB::table('audit_products')->count();
        if ($product != $audit_product) {
            DB::delete('delete from audit_products');
            DB::select('insert into audit_products select * from products');
        }
        DB::select('update audit_products ap set product_quantity=((select nvl(sum(apd.product_quantity),0) from audit_procurement_details apd where apd.product_id=ap.product_id)- (select nvl(sum(atd.product_quantity),0) from audit_transaction_details atd where atd.product_id= ap.product_id))');
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
        $product = new Products;
        $product->product_name = $request->product_name;
        $product->company_id = $request->company_id ?? null;
        $product->product_quantity = $request->product_quantity ?? 0;
        $product->product_unit = $request->product_unit ?? null;
        $product->product_buy_price = $request->product_buy_price ?? 0;
        $product->product_sell_price = $request->product_sell_price ?? 0;
        $product->product_description = $request->product_description ?? null;
        $product->product_status = $request->product_status ?? "ACTIVE";
        $product->save();

        // dd($product->toJson());

        $action = array();
        $action['action_name'] = "insert";
        $action['action_table'] = "products";
        $action['action_description'] = "Menambahkan Produk Baru";
        $action['action_payload'] = $product->toJson();
        ActionsController::store($action);

        return redirect('product')->with('success', 'Data Produk Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products): void
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function movement(Request $request, Products $products)
    {
        //
        $product_movement = DB::select('select * from product_movement where product_id = ?', [$request->id]);
        return view('components.products.product-movement', ['product_movement' => collect($product_movement), 'product' => $products::find($request->id)]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Products $products, Companies $companies)
    {
        //
        $route = "product.update";
        return view('components.products.product-modal', ['route' => $route, 'product' => $products::find($request->id), 'companies' => $companies::select('company_id', 'company_name')->orderBy('company_name', 'asc')->get()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products)
    {
        //
        $product = $products::find($request->id);
        $product->product_name = $request->product_name;
        $product->company_id = $request->company_id ?? null;
        $product->product_quantity = $request->product_quantity ?? 0;
        $product->product_unit = $request->product_unit ?? null;
        $product->product_buy_price = $request->product_buy_price ?? 0;
        $product->product_sell_price = $request->product_sell_price ?? 0;
        $product->product_description = $request->product_description ?? null;
        $product->product_status = $request->product_status;
        $product->save();

        $action = array();
        $action['action_name'] = "update";
        $action['action_table'] = "products";
        $action['action_description'] = "Memperbarui Produk";
        $action['action_payload'] = $product->toJson();
        ActionsController::store($action);

        return redirect('product')->with('success', 'Data Produk Berhasil Diperbarui');
    }

    public function delete(Request $request)
    {
        $route = "product.destroy";
        return view('components.delete-modal', ['id' => $request->id, 'name' => $request->name, 'route' => $route]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Products $products)
    {
        //
        $action = array();
        $action['action_name'] = "delete";
        $action['action_table'] = "products";
        $action['action_description'] = "Menghapus Produk";
        $action['action_payload'] = $products::find($id)->toJson();
        ActionsController::store($action);

        $products::find($id)->delete();
        return redirect('product')->with('success', 'Data Berhasil Dihapus');
    }
}
