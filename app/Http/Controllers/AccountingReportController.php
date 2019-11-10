<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\ReportsExport;
use Illuminate\Http\Request;

class AccountingReportController extends Controller
{
    public function index(Request $request)
    {
    	return view('accounting.reports');
    }

    public function exportExcel(Request $request)
    {
    	if ( $request->client == 'all' ) {
    		if ( $request->status == 'all' ) {
    			$invoices = auth()->user()->getOrganization()->invoicesNotDeleted;
    		} else {
    			$invoices = auth()->user()->getOrganization()->invoicesNotDeleted()->where('status', $request->status)->get();
    		}
    	} else {
    		if ( $request->status == 'all' ) {
    			$invoices = auth()->user()->getOrganization()->invoicesNotDeleted()->where('client_id',$request->client)->get();
    		} else {
    			$invoices = auth()->user()->getOrganization()->invoicesNotDeleted()->where('client_id',$request->client)->where('status', $request->status)->get();
    		}
    	}

    	$invoiceType = $request->type;

        if (!($invoiceType == 'all')) {
    		$invoices = $invoices->filter(function($invoice) use ($invoiceType){
    			return $invoice->type == $invoiceType;
    		});
    	}
    	
    	if ($request->dateType == 'date') {
    		$name = 'Informe trimestre '.$request->quarter.' del '.$request->year;
    		switch ($request->quarter) {
    			case '1':
    				$dateFrom = $request->year.'-01-01 00:00:00';
    				$dateTo = $request->year.'-03-31 23:59:59';
    				break;
    			case '2':
    				$dateFrom = $request->year.'-04-01 00:00:00';
    				$dateTo = $request->year.'-06-30 23:59:59';
    				break;
    			case '3':
    				$dateFrom = $request->year.'-07-01 00:00:00';
    				$dateTo = $request->year.'-09-31 23:59:59';
    				break;
    			case '4':
    				$dateFrom = $request->year.'-10-01 00:00:00';
    				$dateTo = $request->year.'-12-31 23:59:59';
    				break;
    			
    			default:
    				$dateFrom = $request->year.'-01-01 00:00:00';
    				$dateTo = $request->year.'-12-31 23:59:59';
    				break;
    		}
	    	$invoices = $invoices->whereBetween('date', [$dateFrom, $dateTo]);
    	}

    	if ( $request->dateType == 'custom' ) {
    		$name = 'Informe entre'.$request->dateFrom.' a '.$request->dateTo;
	    	$invoices = $invoices->whereBetween('date', [$request->dateFrom, $request->dateTo]);
    	}

    	if ( !($request->series == 'all') ) {
            $serie = $request->series;
            $invoices = $invoices->filter(function($invoice) use ($serie) {
                return explode('-', $invoice->doc_number)[0] == $serie;
            });
        }
		
		$name .= '.xls';
    	return Excel::download(new ReportsExport($invoices), $name);
    }
}
