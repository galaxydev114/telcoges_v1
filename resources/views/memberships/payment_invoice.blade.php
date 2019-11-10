@extends('template.main', ['activePage' => 'invoice-list', 'titlePage' => __('Factura')])

@section('inlinecss')
    <link href="{{ asset('css/printinvoice.css') }}" rel="stylesheet" type="text/css" media="print" />
@endsection

@section('content')
  <div class="content print-margin">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-info hidden-print">
              <h4 class="card-title ">{{ __('Pago') }}</h4>
              <p class="card-category">{{ $payment->id }}</p>
            </div>
            <div class="card-body">
              <!--Author      : @arboshiki-->
<div id="invoice">
	<div class="form-row">
		<div class="col text-right">
			<button class="hidden-print" id="print-button">
				<i class="material-icons">print</i>
			</button>
		</div>
	</div>
	<hr style="background-color: #ddd;">
    
    <div class="invoice overflow-auto print-margin">
      <div style="min-width: auto">
        <header>
			<div class="row">
				<div class="col">
				  <img src="{{ asset('storage/images/organization/1591894980_logo-df450.png') }}" data-holder-rendered="true" class="img-fluid"/>
				</div>
				<div class="col company-details" width="auto">
					<h4 class="name">
						 Telefonia Cobreces SL
					</h4>
					<div>B39868443</div>
					<div>Mendez Nuñez 10 bajo</div>
					<div>Santander (39002), Cantabria, España</div>
					<div>info@diverphone.com</div>
				    <div>942074424</div>
				</div>
            </div>
        </header>
		<main>
			<div class="row contacts">
				<div class="col invoice-to">
					<div class="text-gray-light">Facturado a:</div>
					<h3 class="to">{{ $payment->suscription->organization->name }}</h3>
					<div class="address">{{ $payment->suscription->organization->address }}</div>
					<div class="email"><a href="mailto:{{ $payment->suscription->organization->email }}" style="color: black;">{{ $payment->suscription->organization->email }}</a></div>
				</div>
				<div class="col invoice-details">
					<h3 class="invoice-id">{{ $payment->id }}</h3>
					<div class="date">Fecha: {{ $payment->created_at }}</div>
					<div class="date">Fecha de vencimiento: {{ $payment->date_to }}</div>
				</div>
			</div>
			<div class="" style="overflow-x:auto">
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th class="text-left">Descripción</th>
						<th class="text-right">Subtotal</th>
						<th class="text-right" colspan="3">Iva</th>
						<th class="text-right">Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					  <td class="no">{{ $payment->id }}</td>
						<td class="text-left">
							<h3>{{ $payment->suscription->membership->name }}</h3>
						</td>
						<td class="unit">{{ $payment->sub_total }}</td>
						<td class="unit" colspan="3">{{ $payment->tax }}</td>
						<td class="total">{{ $payment->sub_total + $payment->tax }}</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="4" class="text-left"></td>
						<td colspan="2">SUBTOTAL</td>
						<td>{{ $payment->sub_total }}</td>
					</tr>
					<tr>
						<td colspan="4" class="text-left">{{ __('Forma de pago: Tarjeta de credito') }}</td>
						<td colspan="2">IVA {{ $payment->tax_rate }}%</td>
						<td>{{ $payment->tax }}</td>
					</tr>
					<tr>
						<td colspan="4"></td>
						<td colspan="2">TOTAL</td>
						<td>{{ $payment->sub_total + $payment->tax }}</td>
					</tr>
				</tfoot>
			</table>
			</div>
			{{--
			<div class="thanks">Thank you!</div>
			--}}
			<div class="notices">
			</div>
		</main>
            <footer>
                
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
              
            </div>
          </div>
        </div>
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