<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use DataTables;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{

    public function list()
    {
        return view('content.invoices');
    }

    public function datatable(Request $request)
    {

        $paymentService = new PaymentService();
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');

        $pageIndex = ($start / $length) + 1;
        $pageSize = $length;

        $input = [
            'pageIndex' => $pageIndex,
            'pageSize' => (int)$pageSize,
            'search' => $searchValue,
            'method' => 'paymentDt',
            'type' => 'paymentDt'
        ];

        $payments = $paymentService->list($input);



        return DataTables::of($payments)->toJson();

    }

    public function getBill($id)
    {

        $paymentService = new PaymentService();
        $input = [
            'pageIndex' => 1,
            'pageSize' => 1,
            'search' => null,
            'method' => 'paymentDt',
            'type' => 'paymentDt'
        ];

        $payments = $paymentService->list($input);

        return view('content.view-invoice',['paymentData' => $payments->where('id', $id)->first()->toArray()]);

    }



}
