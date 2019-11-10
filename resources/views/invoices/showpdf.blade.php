@extends('layouts.appPDF', ['activePage' => 'invoice-list', 'titlePage' => __('Factura')])

@section('inlinecss')
	<style>
		#invoice{
		    padding: 30px;
		}

		.invoice {
		    position: relative;
		    background-color: #FFF;
		    min-height: 680px;
		    padding: 15px
		}

		.invoice header {
		    padding: 10px 0;
		    margin-bottom: 20px;
		    border-bottom: 1px solid #000
		}

		.invoice .company-details {
		    text-align: right
		}

		.invoice .company-details .name {
		    margin-top: 0;
		    margin-bottom: 0
		}

		.invoice .contacts {
		    margin-bottom: 20px
		}

		.invoice .invoice-to {
		    text-align: left
		}

		.invoice .invoice-to .to {
		    margin-top: 0;
		    margin-bottom: 0
		}

		.invoice .invoice-details {
		    text-align: right
		}

		.invoice .invoice-details .invoice-id {
		    margin-top: 0;
		    color: #000;
		}

		.invoice main {
		    padding-bottom: 50px
		}

		.invoice main .thanks {
		    margin-top: -100px;
		    font-size: 1.2em;
		    margin-bottom: 50px;
		}

		.invoice main .notices {
		    padding-left: 6px;
		    border-left: 6px solid #3989c6;
		}

		.invoice main .notices .notice {
		    font-size: 12px;
		}

		.invoice table {
		    width: 100%;
		    border-collapse: collapse;
		    border-spacing: 0;
		    margin-bottom: 20px;
		}

		.invoice table td,.invoice table th {
		    padding: 15px;
		    background: #eee;
		    border-bottom: 1px solid #fff;
		}

		.invoice table th {
		    white-space: nowrap;
		    font-weight: 400;
		    font-size: 12px;
		}

		.invoice table td h3 {
		    margin: 0;
		    font-weight: 400;
		    color: #3989c6;
		    font-size: 12px;
		}

		.invoice table .qty,.invoice table .total,.invoice table .unit {
		    text-align: right;
		    font-size: 12px;
		}

		.invoice table .no {
		    color: #fff;
		    font-size: 12px;
		    background: #3989c6;
		}

		.invoice table .unit {
		    background: #ddd;
		}

		.invoice table .total {
		    background: #3989c6;
		    color: #fff;
		}

		.invoice table tbody tr:last-child td {
		    border: none;
		}

		.invoice table tfoot td {
		    background: 0 0;
		    border-bottom: none;
		    /*white-space: nowrap;*/
		    text-align: right;
		    padding: 10px 20px;
		    font-size: 12px;
		    border-top: 1px solid #aaa;
		}

		.invoice table tfoot tr:first-child td {
		    border-top: none;
		}

		.invoice table tfoot tr:last-child td {
		    color: #000;
		    font-size: 14px;
		    border-top: 1px solid #000;
		}

		.invoice table tfoot tr td:first-child {
		    border: none;
		}

		.invoice footer {
		    width: 100%;
		    text-align: center;
		    color: #777;
		    border-top: 1px solid #aaa;
		    padding: 8px 0;
		}
	</style>
@endsection
<div id="invoice">
	<div class="form-row">
		<div class="col-md-1 text-left">
			<div class="text-left">
				<label for="status">@if($invoice->status == 'payed') Pagada @else Pendiente @endif</label>
			</div>
		</div>
	</div>
	<hr style="background-color: #ddd;">
    
    <div class="invoice overflow-auto print-margin">
      <div style="min-width: auto">
        <header>
			<div class="row">
				<div class="col" style="position: relative;padding-right: 15px;padding-left: 15px;">
				  <img src="{{ $logo }}" data-holder-rendered="true" style="max-width: 35%;" />
				</div>
				<div class="col company-details" width="auto" style="position: absolute;top: 0px;">
					<h4 class="name">
						{{ auth()->user()->getOrganization()->commercial_name }}
					</h4>
					<div>{{ auth()->user()->getOrganization()->nif }}</div>
					<div>{{ auth()->user()->getOrganization()->address }}</div>
					<div>{{ auth()->user()->getOrganization()->city }} ({{ auth()->user()->getOrganization()->postal_code }}), {{ auth()->user()->getOrganization()->state }}, {{ auth()->user()->getOrganization()->country }}</div>
					<div>{{ auth()->user()->getOrganization()->email }}</div>
				    <div>{{ auth()->user()->getOrganization()->phone }}</div>
				</div>
            </div>
        </header>
		<main>
			<div class="row contacts">
				<div class="col invoice-to">
					<div class="text-gray-light">Facturado a:</div>
					<h3 class="to">{{ $invoice->client->name }}</h3>
					@if ( $invoice->type == 'sell' )
						<div class="text-gray-light">NIF: {{ $invoice->client->nif }}</div>
					@endif
					<div class="address">{{ $invoice->client->address }}</div>
					<div class="address">{{ $invoice->client->postal_code }} - {{ $invoice->client->province }}</div>
					
				</div>
				<div class="col invoice-details">
					<h3 class="invoice-id">{{ $invoice->doc_number }}</h3>
					<div class="date">Fecha: {{ $invoice->date }}</div>
					<div class="date">Fecha de vencimiento: {{ $invoice->due_date }}</div>
				</div>
			</div>
			<div class="" style="overflow-x:auto">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th class="text-left">Descripción</th>
						<th class="text-right">Cantidad</th>
						<th class="text-right">Precio</th>
						<th class="text-right">Subtotal</th>
						<th class="text-right">Iva</th>
						<th class="text-right">Total</th>
					</tr>
				</thead>
				<tbody>
					@foreach( $invoice->items as $key => $item)
					<tr>
					  <td class="no">{{ $key }}</td>
						<td class="text-left"><h3>{{ $item->name }}</h3>{{ $item->description }}</td>
						<td class="qty">{{ $item->quantity }}</td>
						<td class="unit">{{ $item->price }}</td>
						<td class="unit">{{ $item->total }}</td>
						<td class="unit">{{ $item->tax }}</td>
						<td class="total">{{ $item->grand_total }}</td>
					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="text-left"></td>
						<td colspan="2">SUBTOTAL</td>
						<td>{{ $invoice->total }}</td>
					</tr>
					<tr>
						<td colspan="4" class="text-left">{{ __('Forma de pago:') }} {{ $invoice->pay_way }}</td>
						<td colspan="2">IVA {{ $invoice->iva_rate }}%</td>
						<td>{{ $invoice->iva }}</td>
					</tr>
					<tr>
						<td colspan="4"></td>
						<td colspan="2">TOTAL</td>
						<td>{{ $invoice->grand_total }}</td>
					</tr>
				</tfoot>
			</table>
			</div>

			<div class="notices">
				<div>{{ __('Nota:') }}</div>
				<div class="notice">{{ $invoice->comment }}</div>
			</div>
		</main>
            <footer>
                {{ auth()->user()->getOrganization()->legal_terms }}
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>