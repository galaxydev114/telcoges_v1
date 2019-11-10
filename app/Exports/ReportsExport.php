<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReportsExport implements FromView
{
    protected $invoices;
 
    public function __construct($invoices = null)
    {
        $this->invoices = $invoices;
    }

    public function view(): View
    {
        return view('exports.reports', ['invoices' => $this->invoices]);
    }
}
