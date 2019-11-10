<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\InvoiceSerie;
use App\Invoice;
use App\Item;
use Session;
use PDF;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        if (!isset($request->type) || $request->type == 'all') {
           $invoices = auth()->user()->getOrganization()->invoicesNotDeleted();
        } else {
           $invoices = auth()->user()->getOrganization()->invoicesNotDeleted()->where('type', $request->type);
        }
		if($request->type == 'sell'){
			$typeView = 'Ventas';
			$accionBoton = 'Nueva venta';
		} else {
			$typeView = 'Gastos';
			$accionBoton = 'Nuevo Gasto';
		}
    	return view('invoices.index',['invoices' => $invoices->orderBy('date', 'desc')->get(),'type' => $request->type,
		'typeView' => $typeView, 'accionBoton' => $accionBoton]);
    }

    public function show(Request $request)
    {
    	return view('invoices.show', ['invoice' => Invoice::find($request->id)]);
    }

    public function create(Request $request)
    {
        $org = auth()->user()->getOrganization();
        $series = $org->currentInvoiceSeries();

        if ( is_null($series) ) {
            Session::flash('flash_message', __('- Debe crear una serie para poder comenzar a facturar.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        if ( is_null( auth()->user()->organization->paymethods ) ) {
            Session::flash('flash_message', __('- Debes crear los métodos de pago para poder comenzar a facturar.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }
        
		if($request->type == 'sell'){
			$view = array('typeView' => 'Venta','action' => 'Nueva venta' );
		} else {
			$view = array('typeView' => 'Gasto','action' => 'Nuevo gasto' );
		}
        

    	return view('invoices.create', ['series' => $series, 'type' => $request->type, 'view' => $view]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client' => ['required', 'exists:clients,id'],
            'serie_input' => ['required'],
            //'doc_number' => ['string'],
            'date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:pending,payed'],
            'type' => ['required', 'string', 'in:sell,buy'],
            'comment' => ['nullable', 'string', 'min:1'],
            'pay_way' => ['required', 'string'],
            'itemname' => ['required', 'array', 'min:1'],
            'itemname.*' => ['required'],
        ]);
        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Por favor, revise los datos e intente nuevamente 1.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $serie = auth()->user()->getOrganization()->invoiceSeries()->where('id', $request->serie_input)->first();

        $document_number = $serie->serie.'-'.$request->doc_number;
        $exists = auth()->user()->getOrganization()->invoices()->where('doc_number', $document_number)->count();

        if ($exists) {
            Session::flash('flash_message', __('- Serie + Numero ya existen.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $total = floatval(0);
        $iva = floatval(0);
        $grand_total = floatval(0);

        // Calcular total de la factura
        foreach ($request->itemname as $key => $value) {
            $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
            $tax_rate = floatval($request->taxrate[$key] / 100);
            $iva += floatval($amount * $tax_rate);
            $total +=  $amount;
        }
        
        $grand_total =  $total + $iva;

        $invoice_args = $request->only(['date', 'due_date','status', 'type', 'comment', 'pay_way']);
        $invoice_args['client_id'] = $request->client;
        $invoice_args['doc_number'] = $document_number;
        $invoice_args['total'] = round($total, 2);
        $invoice_args['iva'] = round($iva, 2);
        $invoice_args['grand_total'] = round($grand_total, 2);
        $invoice_args['organization_id'] = auth()->user()->getOrganization()->id;
        $invoice_args['custom_invoice_id'] = intval( $request->doc_number );
        $invoice_args['description'] = $request->itemdescription[0];

        try {
            $invoice = Invoice::create($invoice_args);

            // Agregar items de la factura
            foreach ($request->itemname as $key => $value) {
                if ($request->itemqty[$key] == null || $request->itemprice[$key] == null) {
                    Item::where('invoice_id', $invoice->id)->delete();
                    $invoice->delete();
                    Session::flash('flash_message', __('- Por favor, complete todos los datos e intente nuevamente.'));
                    Session::flash('flash_type', 'alert-danger');
                    return back()->withErrors($validator)->withInput();
                }

                $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
                $tax_rate = floatval($request->taxrate[$key] / 100);
                $iva = floatval($amount * $tax_rate);


                Item::create(
                        array(
                                'invoice_id' => $invoice->id,
                                'name' => $value,
                                'description' => $request->itemdescription[$key],
                                'quantity' => $request->itemqty[$key],
                                'price' => $request->itemprice[$key],
                                'tax_rate' => round($tax_rate, 2),
                                'total' => round($amount, 2),
                                'tax' => round($iva, 2),
                                'grand_total' => round(($amount + $iva), 2),
                            )
                        );
            }

            if ($request->type == 'sell') {
                $serie->current_number += 1;
                $serie->save();
            }

        } catch (Exception $e) {
            Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 2.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['error' => 'Try later']);
        }

        $file = $request->file('attached');
        if ( !is_null($file) ) {
            $nombre = time().'_'.$invoice->id.'_'.$file->getClientOriginalName();
            \Storage::disk('local')->put('public/images/organization/'.auth()->user()->getOrganization()->id.'/invoices/'.$nombre,  \File::get($file));
            $invoice->attached = $nombre;
            $invoice->save();
        }

        Session::flash('flash_message', __('+ Factura registrada.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('invoice.show', ['id' => $invoice->id]);
    }

    public function status(Request $request)
    {
        Invoice::where('id', $request->id)->update(['status' => $request->status]);

        return redirect()->route('invoice.show', ['id' => $request->id]);
    }

    public function delete(Request $request)
    {
        $invoice = Invoice::find($request->id);
        if ($invoice->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $type = $invoice->type;
        $invoice->doc_number = $invoice->doc_number . '-deleted';
        $invoice->save();

        if ( $type == 'sell' ) {
            $serie = InvoiceSerie::where('serie', explode('-', $invoice->doc_number)[0])->first();
            if ( $serie->current_number == ($invoice->custom_invoice_id + 1) ) {
                $serie->current_number = $serie->current_number - 1;
                $serie->save();
            }
        }

        if ( $invoice->items()->delete() && $invoice->delete() ) {
            return redirect()->route('invoices.index', ['type' => $type]);
        }

        Session::flash('flash_message', __('- Por favor intente más tarde'));
        Session::flash('flash_type', 'alert-danger');
        return back();
    }

    public function edit(Request $request)
    {
        $invoice =  Invoice::where('id', $request->id)->first();
        if ($invoice->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }
		if($request->type == 'sell'){
			$view = array('typeView' => 'Venta','action' => 'venta' );
		} else {
			$view = array('typeView' => 'Gasto','action' => 'gasto' );
		}
        return view('invoices.edit', [ 'invoice' => $invoice, 'view' => $view, 'type' => $request->type ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client' => ['required', 'exists:clients,id'],
            'date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:pending,payed'],
            'type' => ['required', 'string', 'in:sell,buy'],
            'comment' => ['nullable', 'string', 'min:1'],
            'pay_way' => ['required', 'string'],
            'itemname' => ['required', 'array', 'min:1'],
            'itemname.*' => ['required'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Por favor, revise los datos e intente nuevamente 1.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $total = floatval(0);
        $iva = floatval(0);
        $grand_total = floatval(0);

        // Calcular total de la factura
        foreach ($request->itemname as $key => $value) {
            $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
            $tax_rate = floatval($request->taxrate[$key] / 100);
            $iva += floatval($amount * $tax_rate);
            $total +=  $amount;
        }

        $grand_total =  $total + $iva;

        $invoice_args = $request->only(['client', 'date', 'due_date','status', 'type', 'comment', 'pay_way']);
        $invoice_args['client_id'] = $request->client;
        $invoice_args['total'] = round($total, 2);
        $invoice_args['iva'] = round($iva, 2);
        $invoice_args['grand_total'] = round($grand_total, 2);

        $invoice = Invoice::find($request->id);
        if ($invoice->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }
        try {
            $invoice->update($invoice_args);

            // Agregar items de la factura
            foreach ($request->itemname as $key => $value) {
                if ($request->itemqty[$key] == null || $request->itemprice[$key] == null) {
                    $invoice->items()->delete();
                    Session::flash('flash_message', __('- Por favor, complete todos los datos e intente nuevamente.'));
                    Session::flash('flash_type', 'alert-danger');
                    return back()->withErrors($validator)->withInput();
                } elseif ($key == 0) {
                    $invoice->items()->delete();
                }

                $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
                $tax_rate = floatval($request->taxrate[$key] / 100);
                $iva = floatval($amount * $tax_rate);

                Item::create(
                        array(
                                'invoice_id' => $invoice->id,
                                'name' => $value,
                                'description' => $request->itemdescription[$key],
                                'quantity' => $request->itemqty[$key],
                                'price' => round($request->itemprice[$key], 2),
                                'tax_rate' => round($tax_rate, 2),
                                'total' => round($amount, 2),
                                'tax' => round($iva, 2),
                                'grand_total' => round(($amount + $iva), 2),
                            )
                        );
            }
        } catch (Exception $e) {
            Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 2.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['error' => 'Try later']);
        }

        $file = $request->file('attached');
        if ( !is_null($file) ) {
            $nombre = time().'_'.$invoice->id.'_'.$file->getClientOriginalName();
            if ( !is_null($invoice->attached) ) {
                unlink(storage_path('app/public/images/organization/'.auth()->user()->getOrganization()->id.'/invoices/'.$invoice->attached));
            }
            \Storage::disk('local')->put('public/images/organization/'.auth()->user()->getOrganization()->id.'/invoices/'.$nombre,  \File::get($file));
            $invoice->attached = $nombre;
            $invoice->save();
        }

        Session::flash('flash_message', __('+ Factura registrada.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('invoices.index', ['type' => $invoice->type]);
    }

    public function viewpdf(Request $request)
    {
        $data = ['title' => 'Test PDF'];
        $invoice = Invoice::find($request->id);

        $path = 'storage/images/organization/'.$invoice->organization()->first()->logo;
        if ( is_file($path) ) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logo_base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            $logo_base64 = 'admin/images/default-empty.png';
        }

        $pdf = PDF::loadView('invoices.showpdf', ['invoice' => $invoice,'data' => $data, 'logo' => $logo_base64]);
        return $pdf->stream('Factura-'.$request->id.'.pdf');
    }
}