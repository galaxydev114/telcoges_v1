@extends('template.main', ['activePage' => 'budgets', 'titlePage' => __('Nuevo presupuesto')])

@section('inlinecss')
  <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap2.css') }}">
@endsection

@section('content')
  <input type="hidden" id="url" value=@if(config('app.env') === 'local') "/client/search" @elseif(config('app.env') === 'production') "/admin/public/client/search" @endif>
  <form class="form" method="POST" action="{{ route('budgets.store') }}">
    @csrf
		<div class="row">
			<div class="col input-box">
			  <label for="client">Cliente</label>
			  <select id="select-client" name="client" class="form-control mdb-select" value="{{ old('client') }}" required>
          @foreach ( auth()->user()->getOrganization()->clients as $client )
            <option value="{{ $client->id }}">{{ $client->name }}</option>
          @endforeach
        </select>
        
        @if ($errors->has('client'))
        <div id="client-error" class="error text-danger pl-3" for="client" style="display: block;">
          <strong>{{ $errors->first('client') }}</strong>
        </div>
        @endif
			</div>

			<div class="col input-box">
			  <label for="date">Fecha</label>
			  <input type="date" id="date" name="date" class="form-control" placeholder="Fecha" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
			</div>
		</div>
    
		<div class="card form-row" style="display: flex;flex-direction: column;align-items: center;">
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
        <i class="fa fa-plus-square-o"></i>
      </div>
    </div>

    <div class="container add-items">
      
    </div>

    <div class="form-row">
      <div class="col"></div>
      <div class="col"></div>
      <div class="col"></div>
      <div class="col"></div>
      <div class="col"></div>
      <div class="col">
        <label for="grandSubTotal">Subtotal</label>
        <input id="grandSubTotal" class="form-control" type="text" disabled="" value="0">
      </div>
      <div class="col">
        <label for="grandIva">Iva</label>
        <input id="grandIva" class="form-control" type="text" disabled="" value="0">
      </div>
      <div class="col">
        <label for="grandTotal">Total</label>
        <input id="grandTotal" class="form-control" type="text" disabled="" value="0">
      </div>
    </div>
		<div class="form-row">
			<div class="col input-box">
			  <label for="comment">Nota adicional a mostrar en el presupuesto</label>
			  <textarea class="form-control" name="comment" id="comment" value="{{ old('comment') }}" rows="2"></textarea>
			</div>
		</div>
    <center>
      <div class="col ">
        <button type="submit" class="btn btn-info"><center>{{ __('Guardar') }}&nbsp;&nbsp;</center></button>
        <a href="{{ route( 'budgets.index' ) }}">
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