@extends('template.main', ['activePage' => 'invoice-list', 'titlePage' => __('Factura '.$invoice->id)])

@section('inlinecss')
    <link href="{{ asset('css/printinvoice.css') }}" rel="stylesheet" type="text/css" media="print" />
@endsection

@section('content')
	<div id="invoice">
		<div class="form-row hidden-print">
			@if(auth()->user()->hasAnyRole(['admin','superadmin']))
				<div class="col-md-1 text-left">
					<div class="text-left">
						<form action="{{ route('invoice.status', ['id' => $invoice->id]) }}" id="invoice-status" method="post">
							@csrf
							@method('put')
							<label for="status">Pendiente/Pagada</label><br>
							<label class="toggle text-left">
								<input type="checkbox" @if($invoice->status == 'payed') checked="" @elseif ($invoice->status == 'pending') ''  @endif class ="change-invoice-select" autocomplete="off"/>
								<span class="slider"></span>
							</label>			
							<input type="hidden" name="status" id="status" value="{{$invoice->status}}" required>
						</form>
					</div>
				</div>
			@endif

			<div class="col text-right">
				@if(auth()->user()->hasAnyRole(['admin','superadmin']))
					<form action="{{ route('invoice.edit', ['id' => $invoice->id, 'type' => $invoice->type]) }}" style="float: right;">
						<button class="hidden-print" type="submit" style="cursor: pointer;">
					  		<i class="fa fa-pencil"></i>
						</button>
					</form>
				@endif
				<button class="hidden-print" id="print-button">
					<i class="fa fa-print"></i>
				</button>
			</div>
		</div>
		<hr class="hidden-print" style="background-color: #ddd;">
 
 		<div class="invoice overflow-auto print-margin">
	      <div style="min-width: auto">
	        	<header>
					<div class="row">
						<div class="col">
						  <img src="{{ asset('storage/images/organization/'.auth()->user()->getOrganization()->logo) }}" data-holder-rendered="true" class="img-fluid"/>
						</div>
						<div class="col company-details" width="auto">
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
					
					{{--
					<div class="thanks">Thank you!</div>
					--}}

					<div class="notices">
						<div>{{ __('Nota:') }}</div>
						<div class="notice">{{ $invoice->comment }}</div>
					</div>
				</main>

            <footer class="footer-print">
                {{ auth()->user()->getOrganization()->legal_terms }}
            </footer>
     		</div>
    		<!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
     		<div></div>
 		</div>
	</div>
@endsection

@section('inlinejs')
    <script>
        $(document).ready( function (){
            $('#print-button').on('click', function() {
                window.print();
                return false;
            });
        });
    </script>
@endsection