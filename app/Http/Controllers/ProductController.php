<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\ProductStock;
use App\Product;
use Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
    	return view('products.index', ['products' => auth()->user()->getOrganization()->products()->orderBy('created_at', 'desc')->get()]);
    }

    public function create(Request $request)
    {
    	return view('products.create');
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'cost_tax_rate' => ['required', 'numeric'],
            'serie' => ['array', 'min:1'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        if ( auth()->user()->getOrganization()->products()->where('name', $request->name)->count() ) {
        	Session::flash('flash_message', __('- El producto ya existe.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        try {
            $product = Product::create([
                'code' => $request->code,
            	'name' => $request->name,
    	        'description' => $request->description,
    	        'price' => $request->price,
                'cost' => $request->cost,
                'cost_tax' => ($request->cost * ($request->cost_tax_rate / 100)),
                'cost_tax_rate' => ($request->cost_tax_rate / 100),
    	        'organization_id' => auth()->user()->getOrganization()->id
            ]);

            if ($product) {
                foreach ($request->serie as $key => $value) {
                    if ( !is_null($request->serie[$key]) || !is_null($request->provider[$key]) ) {
                        ProductStock::create([
                            'product_id' => $product->id,
                            'serie' => $request->serie[$key],
                            'provider' => $request->provider[$key],
                            'status' => 1,
                        ]);
                    }
                }

            	Session::flash('flash_message', __('- Producto agregado.'));
                Session::flash('flash_type', 'alert-success');
        		return redirect()->route('products.index');
            }
        } catch (Exception $e) {
            Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 2.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['error' => 'Try later']);
        }

        Session::flash('flash_message', __('- Error, por favor intente más tarde.'));
        Session::flash('flash_type', 'alert-danger');
        return back()->withErrors($validator)->withInput();
    }

    public function delete(Request $request)
    {
    	$product = Product::find($request->id);
        if ($product->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        if ( $product->productStock()->delete() && $product->delete() ) {
        	Session::flash('flash_message', __('- Producto eliminado.'));
            Session::flash('flash_type', 'alert-success');
            return redirect()->route('products.index');
        }

        Session::flash('flash_message', __('- Por favor intente más tarde'));
        Session::flash('flash_type', 'alert-danger');
        return back();
    }

    public function edit(Request $request)
    {
    	$product = Product::find($request->id);
        if ($product->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        return view('products.edit', ['product' => $product]);
    }

    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'code' => ['required', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:40'],
            'description' => ['nullable', 'string', 'max:80'],
            'price' => ['required', 'numeric'],
            'cost' => ['required', 'numeric'],
            'cost_tax_rate' => ['required', 'numeric'],
            'serie' => ['nullable', 'array'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $exists = auth()->user()->getOrganization()->products()->where('name', $request->name)->first();
        
        if ( !is_null($exists) && ($exists->id != $request->productID) ) {
        	Session::flash('flash_message', __('- El producto ya existe.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $product = Product::find($request->productID);

        try {
            $flag = $product->update([
                        'code' => $request->code,
                    	'name' => $request->name,
            	        'description' => $request->description,
            	        'price' => $request->price,
                        'cost' => $request->cost,
                        'cost_tax' => ($request->cost * ($request->cost_tax_rate / 100)),
                        'cost_tax_rate' => ($request->cost_tax_rate / 100),
                    ]);

            if ( $flag && $product->productStock()->delete() ) {
                if ( is_array($request->serie) && count($request->serie) ) {
                    foreach ($request->serie as $key => $value) {
                        if ( !is_null($request->serie[$key]) || !is_null($request->provider[$key]) ) {
                            ProductStock::create([
                                'product_id' => $product->id,
                                'serie' => $request->serie[$key],
                                'provider' => $request->provider[$key],
                                'status' => 1,
                            ]);
                        }
                    }
                }

                Session::flash('flash_message', __('- Producto actualizado.'));
                Session::flash('flash_type', 'alert-success');
                return redirect()->route('products.edit', ['id' => $product->id]);
            }

            Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 2.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['error' => 'Try later']);
        } catch (Exception $e) {
            Session::flash('flash_message', __('+ Por favor, revise los datos e intente nuevamente 3.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['error' => 'Try later']);
        }

        Session::flash('flash_message', __('- Error, por favor intente más tarde.'));
        Session::flash('flash_type', 'alert-danger');
        return back()->withErrors($validator)->withInput();
    }
}
