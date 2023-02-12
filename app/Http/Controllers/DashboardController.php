<?php

namespace App\Http\Controllers;

use App\Models\Procurements;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $month = date('m');
        $transaction_report = DB::select('select * from monthly_transaction_report where month = ?', [$month]);
        $procurement_report = DB::select('select * from monthly_procurement_report where month = ?', [$month]);
        // dd($transaction_report);
        return view('pages.dashboard', ['transaction_report' => collect($transaction_report), 'procurement_report' => collect($procurement_report),]);
    }
}