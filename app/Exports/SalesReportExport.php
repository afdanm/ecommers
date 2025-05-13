<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesReportExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $controller = new \App\Http\Controllers\Admin\SalesReportController;
        $data = $controller->index($this->request)->getData();

        return view('admin.reports.sales.index', $data);
    }
}


