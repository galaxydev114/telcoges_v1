@extends('template.main', ['activePage' => 'repairs', 'titlePage' => __('Nuevo ingreso')])

@section('inlinecss')
  <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap2.css') }}">
@endsection

@section('content')
  <form class="form" method="POST" action="{{ route('repairs.store') }}">
    @csrf
    <div class="bmd-form-group{{ $errors->has('client') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-user"></i>
          </span>
        </div>
        <select id="select-client" name="client" class="form-control mdb-select" value="{{ old('client') }}" required>
            <option value=""></option>
          @foreach ( auth()->user()->getOrganization()->clients as $client )
            <option value="{{ $client->id }}">{{ $client->name }}</option>
          @endforeach
        </select>
      </div>
      @if ($errors->has('client'))
        <div id="client-error" class="error text-danger pl-3" for="client" style="display: block;">
          <strong>{{ $errors->first('client') }}</strong>
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
        <select id="select-brand" name="brand" class="form-control mdb-select" value="{{ old('brand') }}" required>
          <option value=""></option>
          @forelse( auth()->user()->getOrganization()->brands()->get() as $brand )
            @if ($loop->index == 0)
              <option value=""></option>
            @endif
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

    <div class="bmd-form-group{{ $errors->has('model') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-bolt"></i>
          </span>
        </div>
        <select id="select-model" name="model" class="form-control mdb-select" value="{{ old('model') }}" required>
          <option value="null">Debe seleccionar una marca</option>
        </select>
      </div>
      @if ($errors->has('model'))
        <div id="model-error" class="error text-danger pl-3" for="model" style="display: block;">
          <strong>{{ $errors->first('model') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('imei') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-barcode"></i>
          </span>
        </div>
        <input type="text" name="imei" class="form-control" placeholder="{{ __('IMEI del equipo') }}" value="{{ old('imei') }}" required>
      </div>
      @if ($errors->has('imei'))
        <div id="imei-error" class="error text-danger pl-3" for="imei" style="display: block;">
          <strong>{{ $errors->first('imei') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('condition') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-file-word-o"></i>
          </span>
        </div>
        <input type="text" name="condition" class="form-control" placeholder="{{ __('Estado en que se encuentra el equipo') }}" value="{{ old('condition') }}" required>
      </div>
      @if ($errors->has('condition'))
        <div id="condition-error" class="error text-danger pl-3" for="condition" style="display: block;">
          <strong>{{ $errors->first('condition') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('date') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-calendar"></i>
          </span>
        </div>
        <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
      </div>
      @if ($errors->has('date'))
        <div id="date-error" class="error text-danger pl-3" for="date" style="display: block;">
          <strong>{{ $errors->first('date') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('repair') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="ti-settings"></i>
          </span>
        </div>
        <input type="text" name="repair" class="form-control" placeholder="{{ __('Reparación a realizar') }}" value="{{ old('repair') }}" required>
      </div>
      @if ($errors->has('repair'))
        <div id="repair-error" class="error text-danger pl-3" for="repair" style="display: block;">
          <strong>{{ $errors->first('repair') }}</strong>
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
        <input type="number" name="price" class="form-control" placeholder="{{ __('Precio') }}" value="{{ old('price') }}" required>
      </div>
      @if ($errors->has('price'))
        <div id="price-error" class="error text-danger pl-3" for="price" style="display: block;">
          <strong>{{ $errors->first('price') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('note') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-file-text"></i>
          </span>
        </div>
        <input type="text" name="note" class="form-control" placeholder="{{ __('Anotaciones') }}" value="{{ old('note') }}" required>
      </div>
      @if ($errors->has('note'))
        <div id="note-error" class="error text-danger pl-3" for="note" style="display: block;">
          <strong>{{ $errors->first('note') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('anotation') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-meh-o"></i>
          </span>
        </div>
        <input type="text" name="anotation" class="form-control" placeholder="{{ __('Comentarios cliente') }}" value="{{ old('anotation') }}" required>
      </div>
      @if ($errors->has('anotation'))
        <div id="anotation-error" class="error text-danger pl-3" for="anotation" style="display: block;">
          <strong>{{ $errors->first('anotation') }}</strong>
        </div>
      @endif
    </div>

    <div class="bmd-form-group{{ $errors->has('private_anotation') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-key"></i>
          </span>
        </div>
        <input type="text" name="private_anotation" class="form-control" placeholder="{{ __('Comentarios privados') }}" value="{{ old('private_anotation') }}" required>
      </div>
      @if ($errors->has('private_anotation'))
        <div id="private_anotation-error" class="error text-danger pl-3" for="private_anotation" style="display: block;">
          <strong>{{ $errors->first('private_anotation') }}</strong>
        </div>
      @endif
    </div>

    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
    </div>
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
      dropdownParent: 'body',
      persist: true
    });
    $('#select-brand').selectize({
      create: false,
      sortField: {
        field: 'text',
        direction: 'asc'
      },
      dropdownParent: 'body',
      persist: true
    });
    $('#select-model').selectize({
      create: false,
      sortField: {
        field: 'text',
        direction: 'asc'
      },
      dropdownParent: 'body',
      persist: true
    });

    $('#select-brand').on('change', function(e){
      const url = '/admin/public/brand/'+ $(this).val() +'/models';
      $.ajax({
        url: url,
        success: function(response){
          console.log(response.data);
          $('#select-model').selectize()[0].selectize.destroy();
          $('#select-model').empty();
          $('#select-model').append(response.data);
          $('#select-model').selectize({
            create: false,
            sortField: {
              field: 'text',
              direction: 'asc'
            },
            dropdownParent: 'body'
          });
        }
      });
    });
  </script>
@endsection