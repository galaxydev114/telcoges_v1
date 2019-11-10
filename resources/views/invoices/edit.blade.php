@extends('template.main', ['activePage' => 'invoice-list', 'titlePage' => __('Editar '.$view['action'])])

@section('inlinecss')
  <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap2.css') }}">
@endsection

@section('content')
  <input type="hidden" id="url" value=@if(config('app.env') === 'local') "/client/search" @elseif(config('app.env') === 'production') "/admin/public/client/search" @endif>
  <form class="form" method="POST" action="{{ route('invoice.update', ['id' => $invoice->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('put')

    <div class="row">
      <div class="col input-box">
        <label for="client">Cliente</label>
        <select id="select-client" name="client" class="form-control mdb-select" required>
          @foreach ( auth()->user()->getOrganization()->clients as $client )
            <option value="{{ $client->id }}" @if( $invoice->client_id == $client->id ) selected @endif>{{ $client->name }}</option>
          @endforeach
        </select>
        
        @if ($errors->has('client'))
        <div id="client-error" class="error text-danger pl-3" for="client" style="display: block;">
          <strong>{{ $errors->first('client') }}</strong>
        </div>
        @endif
      </div>

      <div class="col input-box">
        <label for="doc_number">Número documentos</label>
        <input type="text" class="form-control" id="doc_number" name="doc_number" value="{{ $invoice->doc_number }}" readonly required>
        <input type="hidden" name="type" id="type" value="{{$invoice->type}}" required>
      </div>
      <div class="col input-box">
        <label for="date">Fecha</label>
        <input type="date" id="date" name="date" class="form-control" placeholder="Fecha" value="{{ $invoice->date }}" required>
      </div>
      <div class="col input-box">
        <label for="due_date">Vencimiento</label>
        <input type="date" id="due_date" name="due_date" class="form-control" placeholder="Vencimiento" value="{{ $invoice->due_date }}" required>
      </div>
      <div class="col input-box">
        <label for="pay_way">Forma de pago</label>
        <select class="form-control mdb-select" name="pay_way" id="pay_way" required>
          <option value="efectivo">Efectivo</option>
          <option value="tdcredito">Tarjeta de Credito</option>
          <option value="tddebito">Tarjeta de debido</option>
        </select>
      </div>
			<div class="col input-box">
				<label for="status">Pendiente/Pagada</label><br>
				<center>
					<label class="toggle">
						<input type="checkbox" class ="update-status" 
						@if ($invoice->status == 'payed') checked=""
						@elseif ($invoice->status == 'pending') '' @endif
						autocomplete="off"/>
						<span class="slider"></span>
					</label>
				</center>
				<input type="hidden" name="status" id="status" value=
				@if ($invoice->status == 'payed') {{'payed'}}
				@elseif ($invoice->status == 'pending') {{'pending'}} @endif
				required>
			</div>
    </div>

    <div class="card row" style="display: flex;flex-direction: column;align-items: center;">
      <div class="card-body">
        <h3 class="card-title">Nueva linea</h3>
      </div>
    </div>

    @if ($errors->has('itemname'))
      <div id="itemname-error" class="error text-danger pl-3" for="itemname" style="display: block;">
        <strong>{{ $errors->first('itemname') }}</strong>
      </div>
    @endif
    @if ($errors->has('itemname.*'))
      <div id="itemname.*-error" class="error text-danger pl-3" for="itemname.*" style="display: block;">
        <strong>{{ $errors->first('itemname.*') }}</strong>
      </div>
    @endif

    <div class="form-row add-item-to-invoice">
      <div class="col" style="display: flex;flex-direction: column;align-items: center;">
        <i class="material-icons">add_circle</i>
      </div>
    </div>

    <div class="container add-items">
      @forelse($invoice->items as $key => $item)
        <div class="form-row">
          <div class="col">
            <input class="form-control" type="text" name="itemname[]" placeholder="Nombre" value="{{ $item->name }}" />
          </div>
          <div class="col">
            <input class="form-control" type="text" name="itemdescription[]" placeholder="Descripción" value="{{ $item->description }}"/>
          </div>
          <div class="col">
            <input id="qty{{$key}}" class="form-control invoice-item-input" line="{{$key}}" type="number" min="1" step="1" name="itemqty[]" placeholder="Cantidad" value="{{ $item->quantity }}"/>
          </div>
          <div class="col">
            <input id="price{{$key}}" class="form-control invoice-item-input" line="{{$key}}" type="number" step="0.01" name="itemprice[]" placeholder="Precio" value="{{ $item->price }}"/>
          </div>
          <div class="col">
            <input id="taxrate{{$key}}" class="form-control invoice-item-input" line="{{$key}}" type="number" name="taxrate[]" value="{{ $item->tax_rate * 100 }}"/>
          </div>
          <div class="col">
            %
          </div>
          <div class="col">
            <input class="form-control total" id="total{{$key}}" type="text" placeholder="total" readonly value="{{ $item->total }}"/>
          </div>
          <input id="subtotal{{$key}}" type="hidden" value="{{ ($item->price * $item->quantity) + (($item->price * $item->quantity) * ($item->tax_rate)) }}">
          <input id="iva{{$key}}" type="hidden" value="{{ (($item->price * $item->quantity) * ($item->tax_rate / 100)) }}">
          <a href="#" class="delete">
            <i class="material-icons">delete</i>
          </a>
        </div>
      @empty
      @endforelse
    </div>

    <div class="form-row">
      <div class="col"></div>
      <div class="col"></div>
      <div class="col"></div>
      <div class="col"></div>
      <div class="col"></div>
      <div class="col">
        <label for="grandSubTotal">Subtotal</label>
        <input id="grandSubTotal" class="form-control" type="text" disabled="" value="{{$invoice->total}}">
      </div>
      <div class="col">
        <label for="grandIva">Iva</label>
        <input id="grandIva" class="form-control" type="text" disabled="" value="{{$invoice->iva}}">
      </div>
      <div class="col">
        <label for="grandTotal">Total</label>
        <input id="grandTotal" class="form-control" type="text" disabled="" value="{{$invoice->grand_total}}">
      </div>
    </div>
    @if ($type == 'buy')
      <div class="form-row">
        <input type="file" name="attached" value="{{ $invoice->attached }}">
      </div>
    @endif
    <div class="form-row">
      <div class="col input-box">
        <label for="comment">Mensaje visible en factura</label>
        <textarea class="form-control" name="comment" id="comment" rows="2">{{ $invoice->comment }}</textarea>
      </div>
    </div>
    <center>
			<div class="col ">
				<button type="submit" class="btn btn-info"><center>{{ __('Guardar') }}&nbsp;&nbsp;</center></button>
				<a href="{{ route( 'invoices.index' , [ 'type' => $invoice->type ] ) }}">
				  <button type="button" class="btn btn-outline-info">
					<center>{{ __('Cancelar') }}</center>
				  </button>
			  </a>
			</div>
		</center>
  </form>
@endsection

@section('inlinejs')
  <script src="{{ asset('js/selectize.js') }}"></script>
  <script>
    $('#select-client').selectize({
      create: false,
      sortField: {
        field: 'text',
        direction: 'asc'
      },
      dropdownParent: 'body'
    });
  </script>
@endsection