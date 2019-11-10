<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\BudgetItem;
use App\Budget;
use App\Client;
use Session;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
    	return view('budgets.index', ['budgets' => auth()->user()->getOrganization()->budgets()->orderBy('created_at', 'desc')->get()]);
    }

    public function create(Request $request)
    {
    	return view('budgets.create');
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'client' => ['required', 'exists:clients,id'],
            'date' => ['required', 'date'],
            'comment' => ['nullable', 'string', 'min:1'],
            'itemname' => ['required', 'array', 'min:1'],
            'itemname.*' => ['required'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Por favor, revise los datos e intente nuevamente 1.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

    	if ( auth()->user()->getOrganization()->currentBudget() ) {
    		$new_id = auth()->user()->getOrganization()->currentBudget()->custom_id + 1;
    	} else {
    		$new_id = 1;
    	}

    	$total = floatval(0);
        $iva = floatval(0);
        $grand_total = floatval(0);

        // Calcular total del presupuesto
        foreach ($request->itemname as $key => $value) {
            $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
            $tax_rate = floatval($request->taxrate[$key] / 100);
            $iva += floatval($amount * $tax_rate);
            $total +=  $amount;
        }

        $grand_total =  $total + $iva;

        $budget_args = $request->only(['date', 'comment']);
        $budget_args['client_id'] = $request->client;
        $budget_args['custom_id'] = $new_id;
        $budget_args['total'] = round($total, 2);
        $budget_args['iva'] = round($iva, 2);
        $budget_args['iva_rate'] = $tax_rate;
        $budget_args['grand_total'] = round($grand_total, 2);
        $budget_args['organization_id'] = auth()->user()->getOrganization()->id;


    	try {
    		$budget = Budget::create($budget_args);

            // Agregar items al presupuesto
            foreach ($request->itemname as $key => $value) {
                if ($request->itemqty[$key] == null || $request->itemprice[$key] == null) {
                    BudgetItem::where('budget_id', $budget->id)->delete();
                    $budget->delete();
                    Session::flash('flash_message', __('- Por favor, complete todos los datos e intente nuevamente.'));
                    Session::flash('flash_type', 'alert-danger');
                    return back()->withErrors($validator)->withInput();
                }

                $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
                $tax_rate = floatval($request->taxrate[$key] / 100);
                $iva = floatval($amount * $tax_rate);


                BudgetItem::create(
                        array(
                                'budget_id' => $budget->id,
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

        } catch (Exception $e) {
            Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 2.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['error' => 'Try later']);
        }

        Session::flash('flash_message', __('+ Presupuesto creado.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('budgets.index');
    }

    public function edit(Request $request)
    {
    	$budget = Budget::find($request->id);
        if ($budget->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) 
        {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

    	return view('budgets.edit', ['budget' => Budget::find($request->id)]);
    }

    public function update(Request $request)
    {
    	$budget = Budget::find($request->id);
        if ($budget->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) 
        {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $validator = Validator::make($request->all(), [
            'client' => ['required', 'exists:clients,id'],
            'date' => ['required', 'date'],
            'comment' => ['nullable', 'string', 'min:1'],
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

        // Calcular total del presupuesto
        foreach ($request->itemname as $key => $value) {
            $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
            $tax_rate = floatval($request->taxrate[$key] / 100);
            $iva += floatval($amount * $tax_rate);
            $total +=  $amount;
        }

        $grand_total =  $total + $iva;

        $budget_args = $request->only(['date', 'comment']);
        $budget_args['client_id'] = $request->client;
        $budget_args['total'] = round($total, 2);
        $budget_args['iva'] = round($iva, 2);
        $budget_args['iva_rate'] = $tax_rate;
        $budget_args['grand_total'] = round($grand_total, 2);
        $budget_args['organization_id'] = auth()->user()->getOrganization()->id;


    	try {
    		$budget->update($budget_args);

            // Agregar items al presupuesto
            foreach ($request->itemname as $key => $value) {
                if ($request->itemqty[$key] == null || $request->itemprice[$key] == null) {
                    $budget->items()->delete();
                    Session::flash('flash_message', __('- Por favor, complete todos los datos e intente nuevamente.'));
                    Session::flash('flash_type', 'alert-danger');
                    return back()->withErrors($validator)->withInput();
                } elseif ($key == 0) {
                    $budget->items()->delete();
                }

                $amount = floatval($request->itemprice[$key]) * intval($request->itemqty[$key]);
                $tax_rate = floatval($request->taxrate[$key] / 100);
                $iva = floatval($amount * $tax_rate);


                BudgetItem::create(
                        array(
                                'budget_id' => $budget->id,
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

        } catch (Exception $e) {
            Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 2.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['error' => 'Try later']);
        }

        Session::flash('flash_message', __('+ Presupuesto actualizado.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('budgets.show', ['id' => $request->id]);
    }

    public function delete(Request $request)
    {
    	$budget = Budget::find($request->id);
        if ($budget->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) 
        {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        if ( $budget->items()->delete() && $budget->delete() ) {
	    	Session::flash('flash_message', __('+ Presupuesto borrado.'));
	        Session::flash('flash_type', 'alert-success');
	        return redirect()->route('budgets.index');
        }

        Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 2.'));
        Session::flash('flash_type', 'alert-danger');
        return back()->withErrors(['error' => 'Try later']);
    }

    public function show(Request $request)
    {
    	$budget = Budget::find($request->id);
        if ($budget->organization_id != auth()->user()->getOrganization()->id ) 
        {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        return view('budgets.show', ['budget' => $budget]);
    }

    public function status(Request $request)
    {
        $budget = Budget::find($request->budgetid);

        if (! ($budget->organization_id == auth()->user()->getOrganization()->id) ) {
            Session::flash('flash_message', __('- Acción no autorizada.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $budget->status = $request->status;
        $budget->save();
        return response()->json(['response' => 'success', 'status' => $request->status], 200);
    }
}