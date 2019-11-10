@extends('template.main', ['activePage' => 'products', 'titlePage' => __('Nuevo producto')])

@section('content')
  <form class="form" method="POST" action="{{ route('products.store') }}">
    @csrf
    <div class="bmd-form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-barcode"></i>
          </span>
        </div>
        <input oninput="this.value = this.value.toUpperCase()" type="text" name="code" id="code" class="form-control" placeholder="{{ __('Código') }}">
      </div>
      @if ($errors->has('code'))
        <div id="code-error" class="error text-danger pl-3" for="code" style="display: block;">
          <strong>{{ $errors->first('code') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-keyboard-o"></i>
          </span>
        </div>
        <input type="text" name="name" class="form-control" placeholder="{{ __('Nombre del producto') }}" value="{{ old('name') }}" required>
      </div>
      @if ($errors->has('name'))
        <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
          <strong>{{ $errors->first('name') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('description') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-file-text"></i>
          </span>
        </div>
        <input type="text" name="description" class="form-control" placeholder="{{ __('Descripción del producto') }}" value="{{ old('description') }}" required>
      </div>
      @if ($errors->has('description'))
        <div id="description-error" class="error text-danger pl-3" for="description" style="display: block;">
          <i class="fa fa-book"></i>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('price') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-euro"></i>
          </span>
        </div>
        <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="{{ __('Precio sin IVA') }}" required>
      </div>
      @if ($errors->has('price'))
        <div id="price-error" class="error text-danger pl-3" for="price" style="display: block;">
          <strong>{{ $errors->first('price') }}</strong>
        </div>
      @endif
    </div>  

    <div class="bmd-form-group{{ $errors->has('cost') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-money"></i>
          </span>
        </div>
        <input type="number" step="0.01" name="cost" id="cost" class="form-control" placeholder="{{ __('Coste') }}" required>
      </div>
      @if ($errors->has('cost'))
        <div id="cost-error" class="error text-danger pl-3" for="cost" style="display: block;">
          <strong>{{ $errors->first('cost') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('cost_tax_rate') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-bank"></i>
          </span>
        </div>
        <input type="number" step="0.01" name="cost_tax_rate" id="cost_tax_rate" class="form-control" placeholder="{{ __('% Impuesto coste') }}" required>
      </div>
      @if ($errors->has('cost_tax_rate'))
        <div id="cost_tax_rate-error" class="error text-danger pl-3" for="cost_tax_rate" style="display: block;">
          <strong>{{ $errors->first('cost_tax_rate') }}</strong>
        </div>
      @endif
    </div>

    <div class="card">
      <div class="card-header card-header-warning">
        Stock
      </div>
      <div class="">
        <div class="col bmd-form-group mt-3">
          <div class="input-group">
            <button class="btn btn-info add-product-item">Agregar serie</button>
          </div>
        </div>
      </div>
      <div class="card-body product-items">
        <div class="form-row">
          <div class="col bmd-form-group{{ $errors->has('serie') ? ' has-danger' : '' }} mt-3">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fa fa-code"></i>
                </span>
              </div>
              <input oninput="this.value = this.value.toUpperCase()" type="text" name="serie[]" id="serie1" class="form-control" placeholder="{{ __('Serie') }}">
            </div>
            @if ($errors->has('serie'))
              <div id="serie-error" class="error text-danger pl-3" for="serie" style="display: block;">
                <strong>{{ $errors->first('serie') }}</strong>
              </div>
            @endif
          </div>
          <div class="col bmd-form-group{{ $errors->has('provider') ? ' has-danger' : '' }} mt-3">
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">
                  <i class="fa fa-crosshairs"></i>
                </span>
              </div>
              <input type="text" name="provider[]" whorow="1" id="provider1" class="form-control provider-autocomplete" placeholder="{{ __('Proveedor') }}" autocomplete="off">
            </div>
            @if ($errors->has('provider'))
              <div id="provider-error" class="error text-danger pl-3" for="provider" style="display: block;">
                <strong>{{ $errors->first('price') }}</strong>
              </div>
            @endif

            <div id="providerList1" whorow="1">
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer ml-auto mr-auto">
        <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
      </div>
    </div>
  </form>

  <input type="hidden" id="url" value=@if(config('app.env') === 'local') "/provider/search" @elseif(config('app.env') === 'production') "/admin/public/provider/search" @endif>
@endsection