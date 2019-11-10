@extends('template.main', ['activePage' => 'repair-models', 'titlePage' => __('Nuevo modelo')])

@section('content')
  <form class="form" method="POST" action="{{ route('repairs.models.store') }}">
    @csrf

    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-bolt"></i>
          </span>
        </div>
        <input type="text" name="name" class="form-control" placeholder="{{ __('Nombre del modelo...') }}" value="{{ old('name') }}" required>
      </div>
      @if ($errors->has('name'))
        <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
          <strong>{{ $errors->first('name') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('brand') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-building"></i>
          </span>
        </div>
        <select type="text" name="brand" class="form-control mdb-select" placeholder="{{ __('Nombre de la marca...') }}" value="{{ old('brand') }}" required>
          @forelse( auth()->user()->getOrganization()->brands()->get() as $brand )
            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
          @empty
          <option value="null">Debe crear una marca primero</option>
          @endforelse
        </select>
      </div>
      @if ($errors->has('brand'))
        <div id="brand-error" class="error text-danger pl-3" for="brand" style="display: block;">
          <strong>{{ $errors->first('brand') }}</strong>
        </div>
      @endif
    </div>

    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
    </div>
  </form>

  <input type="hidden" id="url" value=@if(config('app.env') === 'local') "/provider/search" @elseif(config('app.env') === 'production') "/admin/public/provider/search" @endif>
@endsection