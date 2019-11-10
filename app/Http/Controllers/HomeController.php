<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Service;
use App\Invoice;
use App\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $invoices = auth()->user()->getOrganization()->invoicesNotDeleted()->get();
        $sell_invoices_count = $invoices->where('type', 'sell')->count();
        $buy_invoices_count = $invoices->where('type', 'buy')->count();
        $clients_count = auth()->user()->getOrganization()->clientsNotDeleted()->count();

        $iva = floatval(0);
        $si = $invoices->where('type', 'sell');
        $ingresos = floatval(0);
        $cobros_pendientes = floatval(0);
        foreach ($si as $key => $value) {
            if ($value->status == 'pending') {
                $cobros_pendientes += $value->grand_total;
            }
            if ($value->status == 'payed') {
                $ingresos += $value->grand_total;
            }
            $iva += $value->iva;
        }

        $bi = $invoices->where('type', 'buy');
        $gastos = floatval(0);
        $pagos_pendientes = floatval(0);
        foreach ($bi as $key => $value) {
            if ($value->status == 'pending') {
                $pagos_pendientes += $value->grand_total;
            }
            if ($value->status == 'payed') {
                $gastos += $value->grand_total;
            }
            $iva -= $value->iva;
        }

        return view('dashboard', [
            'sell_invoices_count' => $sell_invoices_count,
            'buy_invoices_count' => $buy_invoices_count,
            'clients_count' => $clients_count,
            'ingresos' => $ingresos,
            'gastos' => $gastos,
            'cobros_pendientes' => $cobros_pendientes,
            'pagos_pendientes' => $pagos_pendientes,
            'iva' => $iva
        ]);
    }

    public function productAndServicesAutocomplete(Request $request)
    {
        $response = '<ul id="name-list" class="name-list">';
        $products = Product::where("name","LIKE","%{$request->str}%")->where('organization_id', auth()->user()->getOrganization()->id)->get();
        $services = Service::where("name","LIKE","%{$request->str}%")->where('organization_id', auth()->user()->getOrganization()->id)->get();

        foreach ($products as $key => $value) {
            $response .= '<li class="pl" ind="'.$request->ind.'" ';
            if($request->type == 'sell') {
                $response .= 'price="'.$value->price.'" ';
            }
            if($request->type == 'buy') {
                $response .= 'price="'.$value->cost.'" ';
            }

            $response .= 'description="'.$value->description.'" name="'.$value->name.'">'.$value->name.'</li>';
        }

        foreach ($services as $key => $value) {
            $response .= '<li class="pl" ind="'.$request->ind.'" price="'.$value->price.'" description="'.$value->description.'" name="'.$value->name.'">'.$value->name.'</li>';
        }

        return $response .= '</ul>';
    }
}
