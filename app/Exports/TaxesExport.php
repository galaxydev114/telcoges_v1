<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class TaxesExport implements FromView
{
    protected $quarters;
 
    public function __construct($quarters = null)
    {
        $this->quarters = $quarters;
    }

    public function view(): View
    {
        return view('exports.taxes_export', ['quarters' => $this->quarters]);
    }
}
