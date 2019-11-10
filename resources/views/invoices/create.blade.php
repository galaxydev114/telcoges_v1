@extends('template.main', ['activePage' => 'invoice-list', 'titlePage' => __($view['action'])])

@section('inlinecss')
  <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap2.css') }}">
@endsection

@section('content')
  <input type="hidden" id="url" value=@if(config('app.env') === 'local') "/client/search" @elseif(config('app.env') === 'production') "/admin/public/client/search" @endif>
  <form class="form" method="POST" action="{{ route('invoice.store') }}" enctype="multipart/form-data">
    @csrf
		<div class="row">
			<div class="col input-box">
			  <label for="client">Cliente</label>
        <select id="select-client" name="client" class="form-control mdb-select" value="{{ old('client') }}" required>
          <option value=""></option>
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
			  <label for="serie_input">Serie documento</label>

        <select class="form-control mdb-select" name="serie_input" id="serie_input" required>
          @foreach ($series as $serie)
            <option value="{{ $serie->id }}" url="{{ route('invoice.series.data', $serie->id) }}">{{ $serie->serie }}</option>
          @endforeach
        </select>
			</div>

      <div class="col input-box">
        <label for="doc_number">Número documento</label>
        <input type="text" class="form-control" id="doc_number" name="doc_number" value="@if($type == 'sell') {{ $series[0]->current_number }} @endif" required>
        <input type="hidden" class="form-control" id="type" name="type" value="{{$type}}" required>
      </div>

			<div class="col input-box">
			  <label for="date">Fecha</label>
			  <input type="date" id="date" name="date" class="form-control" placeholder="Fecha" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
			</div>
			<div class="col input-box">
			  <label for="due_date">Vencimiento</label>
			  <input type="date" id="due_date" name="due_date" class="form-control" placeholder="Vencimiento" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
			</div>
			<div class="col input-box">
			  <label for="pay_way">Forma de pago</label>
			  <select class="form-control mdb-select" name="pay_way" id="pay_way" required>
          @forelse ( auth()->user()->organization->paymethods as $pm)
            <option value="{{ $pm }}">{{ $pm }}</option>
          @empty
          @endforelse
			  </select>
			</div>
			<div class="col input-box">
				<label for="status">Pendiente/Pagada</label><br>
				<center>
					<label class="toggle">
						<input type="checkbox" class="update-status" autocomplete="off"/>
						<span class="slider"></span>
					</label>
				</center>
				<input type="hidden" name="status" id="status" value="pending" required>
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
    <div class="row" style="margin-bottom: 10px;">
      <div class="col" style="display: flex;flex-direction: column;align-items: center;">
        <!-- Button trigger modal -->
        <button type="button" class="btn add-item-to-invoice" data-toggle="modal" data-target="#itemModal">
          <i class="fa fa-plus-square-o"></i>
        </button>
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
        <input id="grandSubTotal" class="form-control" type="text" disabled="" value="0" autocomplete="off">
      </div>
      <div class="col">
        <label for="grandIva">Iva</label>
        <input id="grandIva" class="form-control" type="text" disabled="" value="0" autocomplete="off">
      </div>
      <div class="col">
        <label for="grandTotal">Total</label>
        <input id="grandTotal" class="form-control" type="text" disabled="" value="0" autocomplete="off">
      </div>
    </div>
    @if ($type == 'buy')
			<div class="form-row">
        <input type="file" name="attached">
      </div>
    @endif
    <div class="form-row">
			<div class="col input-box">
			  <label for="comment">Mensaje visible en factura</label>
			  <textarea class="form-control" name="comment" id="comment" value="{{ old('comment') }}" rows="2"></textarea>
      </div>
		</div>
    <center>
      <div class="col">
        <button type="submit" class="btn btn-info">
          <center>{{ __('Guardar') }}&nbsp;&nbsp;</center>
        </button>
        <a href="{{ route( 'invoices.index' , [ 'type' => $type ] ) }}">
          <button type="button" class="btn btn-outline-info">
            <center>{{ __('Cancelar') }}</center>
          </button>
         </a>
      </div>
    </center>
  </form>

  <input type="hidden" id="ajaxurl" value="{{ route('productservices.autocomplete', [ 'type' => $type ]) }}">

  <!-- Modal -->
  <div class="modal fade" id="itemModal2" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="itemModalLabel">{{ __('Agregar item') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="text" class="form-control" id="itemName" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cerrar') }}</button>
          <button type="button" class="btn btn-info">{{ __('Agregar') }}</button>
        </div>
      </div>
    </div>
  </div>
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
      dropdownParent: 'body',
      persist: true
    });
  </script>

  @if($type == 'sell')
    <script type="text/javascript">
      $(document).ready(function() {
        $('#serie_input').on('change', function(){
          var optionSelected = $("option:selected", this);
          var selected = $(optionSelected).attr('url');
          $.ajax({
            url: selected,
            success: function(response){
              $('#doc_number').val( response.data.current_number );
            }
          });
        });
      });
    </script>
  @endif
@endsection
