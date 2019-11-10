<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
    	$year = $request->year ? $request->year : Carbon::now()->year;

        $ivas = auth()->user()->getOrganization()->invoices()
        				->select([
                        DB::raw('YEAR(date) as yr'),
                        DB::raw('QUARTER(date) as qt'),
                        DB::raw('sum( case when type="sell" then iva else 0 end ) as iva_soportado'),
                        DB::raw('sum( case when type="buy" then iva else 0 end ) as iva_repercutido'),
                    ])
        			->whereYear('date', $year)
                    ->groupBy(['yr', 'qt'])
                    ->orderBy('created_at', 'asc')
                    ->get();
		
		$ivas = $ivas->groupBy(function ($item, $key) {
		    return substr($item['qt'], 0);
		})->toArray();


        $quarters = array(
        	1 => [ 'yr' => $year, 'qt' => 1, 'iva_soportado' => 0, 'iva_repercutido' => 0 ],
        	2 => [ 'yr' => $year, 'qt' => 2, 'iva_soportado' => 0, 'iva_repercutido' => 0 ],
        	3 => [ 'yr' => $year, 'qt' => 3, 'iva_soportado' => 0, 'iva_repercutido' => 0 ],
        	4 => [ 'yr' => $year, 'qt' => 4, 'iva_soportado' => 0, 'iva_repercutido' => 0 ]
    	);

		foreach ($quarters as $key => &$value) {
			if ( isset($ivas[$key]) ) {
				if ( $ivas[$key][0]['qt'] == $value['qt'] ) {
					$value['iva_soportado'] = $ivas[$key][0]['iva_soportado'];
					$value['iva_repercutido'] = $ivas[$key][0]['iva_repercutido'];
				}
			}
		}

    	return view('accounting.index', ['quarters' => $quarters]);
    }
}
