@extends('template.main', ['activePage' => 'budgets', 'titlePage' => __('Presupuesto '.$budget->custom_id)])

@section('inlinecss')
    <link href="{{ asset('css/printinvoice.css') }}" rel="stylesheet" type="text/css" media="print" />
@endsection

@section('content')

<div id="invoice">
	<div class="form-row hidden-print">
		<div class="col text-left">
			<span class="borderr-{{ $budget->status }}">
				@if ($budget->status == 'pending')
					Pendiente
				@elseif ($budget->status == 'aproved')
					Aprobado
				@elseif ($budget->status == 'rejected')
					Rechazado
				@endif
			</span>
		</div>
		<div class="col text-right">
			@if(auth()->user()->hasAnyRole(['admin','superadmin']))
				<form action="{{ route('budgets.edit', ['id' => $budget->id]) }}" style="float: right;">
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
	<hr style="background-color: #ddd;">
    
    <div class="invoice overflow-auto print-margin">
      <div style="min-width: auto">
        	<header>
				<div class="row">
					<div class="col">
					  <img src="{{ asset('storage/images/organization/'.auth()->user()->getOrganization()->logo) }}" data-holder-rendered="true" class="img-fluid" style="max-width: 70% !important;" />
					</div>
					<div class="col company-details" width="auto">
						<h4 class="name">{{ auth()->user()->getOrganization()->commercial_name }}</h4>
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
						<div class="text-gray-light">{{ __('Presupuesto para:') }}</div>
						<h3 class="to">{{ $budget->clientwithTrashed->name }}</h3>
						<div class="address">{{ $budget->clientwithTrashed->address }}</div>
						<div class="email"><a href="mailto:{{ $budget->clientwithTrashed->email }}" style="color: black;">{{ $budget->clientwithTrashed->email }}</a></div>
					</div>
					<div class="col invoice-details">
						<h3 class="invoice-id">{{ $budget->custom_id }}</h3>
						<div class="date">Fecha: {{ $budget->date }}</div>
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
							@foreach( $budget->items as $key => $item)
							<tr>
							  <td class="budget-no">{{ $key }}</td>
								<td class="text-left"><h3>{{ $item->name }}</h3>{{ $item->description }}</td>
								<td class="qty">{{ $item->quantity }}</td>
								<td class="unit">{{ $item->price }}</td>
								<td class="unit">{{ $item->total }}</td>
								<td class="unit">{{ $item->tax }}</td>
								<td class="budget-total">{{ $item->grand_total }}</td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4" class="text-left"></td>
								<td colspan="2">SUBTOTAL</td>
								<td>{{ $budget->total }}</td>
							</tr>
							<tr>
								<td colspan="4" class="text-left"></td>
								<td colspan="2">IVA {{ $budget->iva_rate }}%</td>
								<td>{{ $budget->iva }}</td>
							</tr>
							<tr>
								<td colspan="4"></td>
								<td colspan="2">TOTAL</td>
								<td>{{ $budget->grand_total }}</td>
							</tr>
						</tfoot>
					</table>
				</div>

				{{--
				<div class="thanks">Thank you!</div>
				--}}

				<div class="notices budget-notices">
					<div>{{ __('Nota:') }}</div>
					<div class="notice">{{ $budget->comment }}</div>
				</div>
			</main>

         <footer>
             {{-- auth()->user()->getOrganization()->legal_terms --}}
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