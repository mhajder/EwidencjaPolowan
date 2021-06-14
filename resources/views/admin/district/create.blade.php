@extends('adminlte::page')

@section('title', 'Tworzenie obwodu')

@section('content_header')
    <h1>Tworzenie obwodu</h1>
@stop

@section('content')
    @include('partials.alerts')
    <form id="district_create" role="form" action="{{ route('district.store') }}" method="post">
    {!! csrf_field() !!}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card card-primary">
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="district_name">Nazwa obwodu*</label>
                                    <input type="text" value="{{ old('district_name') }}" maxlength="50"
                                           class="form-control {{ $errors->has('district_name') ? 'is-invalid' : '' }}"
                                           name="district_name" id="district_name" placeholder="Nazwa obwodu" required>
                                    @if ($errors->has('district_name'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('district_name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="district_code">Kod obwodu*</label>
                                    <input type="text" value="{{ old('district_code') }}" maxlength="15"
                                           class="form-control {{ $errors->has('district_code') ? 'is-invalid' : '' }}"
                                           name="district_code" id="district_code" placeholder="Kod obwodu" required>
                                    @if ($errors->has('district_code'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('district_code') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="district_description">Opis</label>
                                    <textarea rows="3" maxlength="500"
                                              class="form-control {{ $errors->has('district_description') ? 'is-invalid' : '' }}"
                                              name="district_description" id="district_description"
                                              placeholder="Opis">{{ old('district_description') }}</textarea>
                                    @if ($errors->has('district_description'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('district_description') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="district_disabled">Stan*</label>
                                    <select
                                        class="form-control {{ $errors->has('district_disabled') ? 'is-invalid' : '' }}"
                                        name="district_disabled" id="district_disabled">
                                        <option value="0" {{ (old() ? old('district_disabled', true) == false : false) ? 'selected' : '' }}>
                                            Odblokowany
                                        </option>
                                        <option value="1" {{ (old() ? old('district_disabled', true) == true : false) ? 'selected' : '' }}>
                                            Zablokowany
                                        </option>
                                    </select>
                                    @if ($errors->has('district_disabled'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('district_disabled') }}
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
                                <button type="submit" class="btn btn-primary btn-block">Dodaj obwód</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </form>
@stop
