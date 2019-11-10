@extends('template.main', ['activePage' => 'services', 'titlePage' => __('Nuevo servicio')])

@section('content')
  <form class="form" method="POST" action="{{ route('services.store') }}">
    @csrf
    <div class="bmd-form-group{{ $errors->has('code') ? ' has-danger' : '' }}">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-barcode"></i>
          </span>
        </div>
        <input type="text" name="code" id="code" class="form-control" placeholder="{{ __('Código...') }}">
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
        <input type="text" name="name" class="form-control" placeholder="{{ __('Nombre del servicio...') }}" value="{{ old('name') }}" required>
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
        <input type="text" name="description" class="form-control" placeholder="{{ __('Descripción del servicio...') }}" value="{{ old('description') }}" required>
      </div>
      @if ($errors->has('description'))
        <div id="description-error" class="error text-danger pl-3" for="description" style="display: block;">
          <strong>{{ $errors->first('description') }}</strong>
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
        <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="{{ __('Precio...') }}" required>
      </div>
      @if ($errors->has('price'))
        <div id="price-error" class="error text-danger pl-3" for="price" style="display: block;">
          <strong>{{ $errors->first('price') }}</strong>
        </div>
      @endif
    </div>

    <input id="organization_id" name="organization_id" type="hidden" value="{{ auth()->user()->getOrganization()->id }}">
    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
    </div>
  </form>
@endsection