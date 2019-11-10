@extends('template.main', ['activePage' => 'organization-series', 'titlePage' => __('Series de factura')])

@section('content')
<form class="form" method="POST" action="{{ route('invoice.series.store') }}">
  @csrf
    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-registered"></i>
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
    <div class="bmd-form-group{{ $errors->has('serie') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-buysellads"></i>
          </span>
        </div>
        <input type="text" name="serie" class="form-control" placeholder="{{ __('Serie...') }}" value="{{ old('serie') }}" required>
      </div>
      @if ($errors->has('serie'))
        <div id="serie-error" class="error text-danger pl-3" for="serie" style="display: block;">
          <strong>{{ $errors->first('serie') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('start_from') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-line-chart"></i>
          </span>
        </div>
        <input type="number" name="start_from" id="start_from" class="form-control" placeholder="{{ __('Número inicial...') }}" required>
      </div>
      @if ($errors->has('start_from'))
        <div id="start_from-error" class="error text-danger pl-3" for="start_from" style="display: block;">
          <strong>{{ $errors->first('start_from') }}</strong>
        </div>
      @endif
    </div>

   <input id="organization_id" name="organization_id" type="hidden" value="{{ auth()->user()->getOrganization()->id }}">


    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
    </div>
</form>
@endsection