@extends('adminlte::page')

@section('title', 'Edycja obwodu')

@section('content_header')
    <h1>Edycja obwodu</h1>
@stop

@section('content')
    @include('partials.alerts')
    <form id="district_edit" role="form" action="{{ route('district.update', ['id' => $district->id]) }}" method="post">
        {!! csrf_field() !!}
        {{ method_field('patch') }}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card card-primary">
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="district_name">Nazwa obwodu</label>
                                    <input type="text" class="form-control" id="district_name"
                                           value="{{ $district->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="district_code">Kod obwodu</label>
                                    <input type="text" class="form-control" id="district_code"
                                           value="{{ $district->code }}" disabled>
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
                                              name="district_description"
                                              id="district_description"
                                              placeholder="Opis">{{ old('district_description') ?? $district->description }}</textarea>
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
                                <div
                                    class="form-group">
                                    <label for="district_disabled">Stan*</label>
                                    <select
                                        class="form-control {{ $errors->has('district_disabled') ? 'is-invalid' : '' }}"
                                        name="district_disabled" id="district_disabled">
                                        <option value="0" {{ (old() ? old('district_disabled', true) == false : $district->disabled == false) ? 'selected' : '' }}>
                                            Odblokowany
                                        </option>
                                        <option value="1" {{ (old() ? old('district_disabled', true) == true : $district->disabled == true) ? 'selected' : '' }}>
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
                                <button type="submit" class="btn btn-primary btn-block">Edytuj obw??d</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </form>
@stop
