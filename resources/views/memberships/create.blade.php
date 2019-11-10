@extends('template.main', ['activePage' => 'memberships', 'titlePage' => __('Nueva membresia')])

@section('content')
  <form class="form" method="POST" action="{{ route('memberships.store') }}">
    @csrf
    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-star"></i>
          </span>
        </div>
        <input type="text" name="name" class="form-control" placeholder="{{ __('Nombre...') }}" value="{{ old('name') }}" required>
      </div>
      @if ($errors->has('name'))
        <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
          <strong>{{ $errors->first('name') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('frequency') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-refresh"></i>
          </span>
        </div>
        {{--<label for="frequency">Frecuencia</label>--}}
        <select class="form-control bmd-selectpicker" id="frequency" name="frequency">
          <option value="monthly" selected="">Mensual</option>
          <option value="anual">Anual</option>
        </select>
      @if ($errors->has('frequency'))
        <div id="frequency-error" class="error text-danger pl-3" for="frequency" style="display: block;">
          <strong>{{ $errors->first('frequency') }}</strong>
        </div>
      @endif
      </div>
    </div>
    <div class="bmd-form-group{{ $errors->has('price') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-euro"></i>
          </span>
        </div>
        <input type="text" name="price" id="price" class="form-control" placeholder="{{ __('price...') }}" required>
      </div>
      @if ($errors->has('price'))
        <div id="price-error" class="error text-danger pl-3" for="price" style="display: block;">
          <strong>{{ $errors->first('price') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('tax_rate') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-bank"></i>
          </span>
        </div>
        <input type="text" name="tax_rate" id="tax_rate" class="form-control" placeholder="{{ __('% Impuesto...') }}" value="21" required>
      </div>
      @if ($errors->has('tax_rate'))
        <div id="tax_rate-error" class="error text-danger pl-3" for="tax_rate" style="display: block;">
          <strong>{{ $errors->first('tax_rate') }}</strong>
        </div>
      @endif
    </div>
    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
    </div>
  </form>
@endsection