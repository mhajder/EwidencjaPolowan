@extends('adminlte::page')

@section('title', 'Tworzenie rewiru dla obwodu "' . $district->name . '"')

@section('content_header')
    <h1>Tworzenie rewiru dla obwodu "{{ $district->name }}"</h1>
@stop

@section('content')
    @include('partials.alerts')
    <form id="hunting_ground_create" role="form"
          action="{{ route('hunting-ground.store', ['district_id' => $district->id]) }}" method="post">
        {!! csrf_field() !!}
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                <!-- general form elements -->
                <div class="card card-primary">
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_ground_name">Nazwa rewiru*</label>
                                    <input type="text" value="{{ old('hunting_ground_name') }}" maxlength="50"
                                           class="form-control {{ $errors->has('hunting_ground_name') ? 'is-invalid' : '' }}"
                                           name="hunting_ground_name" id="hunting_ground_name"
                                           placeholder="Nazwa rewiru" autofocus required>
                                    @if ($errors->has('hunting_ground_name'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_ground_name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_ground_code">Kod rewiru*</label>
                                    <input type="text" value="{{ old('hunting_ground_code') }}" maxlength="15"
                                           class="form-control {{ $errors->has('hunting_ground_code') ? 'is-invalid' : '' }}"
                                           name="hunting_ground_code" id="hunting_ground_code"
                                           placeholder="Kod rewiru" required>
                                    @if ($errors->has('hunting_ground_code'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_ground_code') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="hunting_ground_description">Opis</label>
                                    <textarea rows="3" maxlength="500"
                                              class="form-control {{ $errors->has('hunting_ground_description') ? 'is-invalid' : '' }}"
                                              name="hunting_ground_description"
                                              id="hunting_ground_description"
                                              placeholder="Opis">{{ old('hunting_ground_description') }}</textarea>
                                    @if ($errors->has('hunting_ground_description'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_ground_description') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_ground_disabled">Stan*</label>
                                    <select
                                        class="form-control {{ $errors->has('hunting_ground_disabled') ? 'is-invalid' : '' }}"
                                        name="hunting_ground_disabled" id="hunting_ground_disabled">
                                        <option
                                            value="0" {{ (old() ? old('hunting_ground_disabled', true) == false : false) ? 'selected' : '' }}>
                                            Odblokowany
                                        </option>
                                        <option
                                            value="1" {{ (old() ? old('hunting_ground_disabled', true) == true : false) ? 'selected' : '' }}>
                                            Zablokowany
                                        </option>
                                    </select>
                                    @if ($errors->has('hunting_ground_disabled'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_ground_disabled') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">Dodaj rewir</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
@stop
