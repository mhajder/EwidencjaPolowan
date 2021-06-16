@extends('adminlte::page')

@section('title', 'Tworzenie polowania')

@section('content_header')
    <h1>Tworzenie polowania dla obwodu "{{ Auth::user()->selectedDistrict->name }}"</h1>
@stop

@section('plugins.Select2', true)
@section('plugins.DateRangePicker', true)

@section('content')
    @include('partials.alerts')
    <form id="hunting_create" role="form" action="{{ route('hunting.store') }}" method="post">
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
                                    <label for="hunting_who">Kto*</label>
                                    <input type="text" class="form-control" id="hunting_who"
                                           value="{{ Auth::user()->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_authorization">Numer upoważnienia*</label>
                                    <select
                                        class="form-control {{ $errors->has('hunting_authorization') ? 'is-invalid' : '' }}"
                                        name="hunting_authorization" id="hunting_authorization" required>
                                        @foreach($authorizations as $authorization)
                                            <option
                                                value="{{ $authorization->id }}" {{ (old() ? old('hunting_authorization') == $authorization->id : false) ? 'selected' : '' }}>
                                                {{ $authorization->number }} ({{ $authorization->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('hunting_authorization'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_authorization') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_grounds">Rewir*</label>
                                    <select
                                        class="form-control select2 {{ $errors->has('hunting_grounds') ? 'is-invalid' : '' }}{{ $errors->has('hunting_grounds.*') ? 'is-invalid' : '' }}"
                                        multiple="multiple" data-placeholder="Wybierz rewiry" name="hunting_grounds[]"
                                        id="hunting_grounds" required>
                                        @foreach($huntingGrounds as $huntingGround)
                                            <option
                                                value="{{ $huntingGround->id }}" {{ (old() ? collect(old('hunting_grounds'))->contains($huntingGround->id) == true : false) ? 'selected' : '' }}>
                                                {{ $huntingGround->name }} ({{ $huntingGround->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('hunting_grounds'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_grounds') }}
                                        </span>
                                    @endif
                                    @if ($errors->has('hunting_grounds.*'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_grounds.*') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            @php
                                if (old('hunting_start')) {
                                    if (Helper::checkIfDateIsValid(old('hunting_start'))) {
                                        $hunting_start = Carbon::parse(old('hunting_start'));
                                        $hunting_start_formatted = $hunting_start->format(Helper::HUNTING_DATE_RANGE_PICKER_FORMAT);
                                        $hunting_start_string = $hunting_start->toRfc1123String();
                                    } else {
                                        $hunting_start_formatted = old('hunting_start');
                                        $hunting_start_string = old('hunting_start');
                                    }
                                } else {
                                    $hunting_start = Helper::getNearestTimeRoundedUpWithMinimum(Carbon::now(), 15, 1);
                                    $hunting_start_formatted = $hunting_start->format(Helper::HUNTING_DATE_RANGE_PICKER_FORMAT);
                                    $hunting_start_string = $hunting_start->toRfc1123String();
                                }
                            @endphp
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_start">Data rozpoczęcia*</label>
                                    <input class="form-control {{ $errors->has('hunting_start') ? 'is-invalid' : '' }}"
                                           id="start" placeholder="Start"
                                           value="{{ $hunting_start_formatted ?? '' }}" required>
                                    <input type="hidden" class="form-control" name="hunting_start" id="hunting_start"
                                           value="{{ $hunting_start_string ?? '' }}" required>
                                    @if ($errors->has('hunting_start'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_start') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @php
                                if (old('hunting_end')) {
                                    if (Helper::checkIfDateIsValid(old('hunting_end'))) {
                                        $hunting_end = Carbon::parse(old('hunting_end'));
                                        $hunting_end_formatted = $hunting_end->format(Helper::HUNTING_DATE_RANGE_PICKER_FORMAT);
                                        $hunting_end_string = $hunting_end->toRfc1123String();
                                    } else {
                                        $hunting_end_formatted = old('hunting_end');
                                        $hunting_end_string = old('hunting_end');
                                    }
                                }
                            @endphp
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_end">Data zakończenia*</label>
                                    <input class="form-control {{ $errors->has('hunting_end') ? 'is-invalid' : '' }}"
                                           id="end" placeholder="Koniec"
                                           value="{{ $hunting_end_formatted ?? '' }}" required>
                                    <input type="hidden" class="form-control" name="hunting_end" id="hunting_end"
                                           value="{{ $hunting_end_string ?? '' }}" required>
                                    @if ($errors->has('hunting_end'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_end') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="hunting_description">Opis</label>
                                    <textarea rows="3" maxlength="500"
                                              class="form-control {{ $errors->has('hunting_description') ? 'is-invalid' : '' }}"
                                              name="hunting_description" id="hunting_description"
                                              placeholder="Opis">{{ old('hunting_description') }}</textarea>
                                    @if ($errors->has('hunting_description'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_description') }}
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
                                <button type="submit" class="btn btn-primary btn-block">Dodaj polowanie</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </form>
@stop

@section('js')
    <script>
        let minDateStart = new Date();
        let maxDateStart = new Date();
        maxDateStart.setDate(maxDateStart.getDate() + 1);
        $('#start').daterangepicker({
            "autoUpdateInput": false,
            "singleDatePicker": true,
            "timePicker": true,
            "timePicker24Hour": true,
            "timePickerIncrement": 15,
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "{{ __('datetimepicker.applyLabel') }}",
                "cancelLabel": "{{ __('datetimepicker.cancelLabel') }}",
                "fromLabel": "{{ __('datetimepicker.fromLabel') }}",
                "toLabel": "{{ __('datetimepicker.toLabel') }}",
                "customRangeLabel": "{{ __('datetimepicker.customRangeLabel') }}",
                "weekLabel": "{{ __('datetimepicker.weekLabel') }}",
                "daysOfWeek": [
                    "{{ __('datetimepicker.daysOfWeek.Su') }}",
                    "{{ __('datetimepicker.daysOfWeek.Mo') }}",
                    "{{ __('datetimepicker.daysOfWeek.Tu') }}",
                    "{{ __('datetimepicker.daysOfWeek.We') }}",
                    "{{ __('datetimepicker.daysOfWeek.Th') }}",
                    "{{ __('datetimepicker.daysOfWeek.Fr') }}",
                    "{{ __('datetimepicker.daysOfWeek.Sa') }}"
                ],
                "monthNames": [
                    "{{ __('datetimepicker.monthNames.January') }}",
                    "{{ __('datetimepicker.monthNames.February') }}",
                    "{{ __('datetimepicker.monthNames.March') }}",
                    "{{ __('datetimepicker.monthNames.April') }}",
                    "{{ __('datetimepicker.monthNames.May') }}",
                    "{{ __('datetimepicker.monthNames.June') }}",
                    "{{ __('datetimepicker.monthNames.July') }}",
                    "{{ __('datetimepicker.monthNames.August') }}",
                    "{{ __('datetimepicker.monthNames.September') }}",
                    "{{ __('datetimepicker.monthNames.October') }}",
                    "{{ __('datetimepicker.monthNames.November') }}",
                    "{{ __('datetimepicker.monthNames.December') }}"
                ],
                "firstDay": 1
            },
            "minDate": minDateStart,
            "maxDate": maxDateStart,
            "showDropdowns": false,
            "opens": "center"
        }, function (start) {
            $('#hunting_start').val(start);
        });

        $('#start').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY H:mm'));
        });

        $('#start').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        let minDateEnd = new Date();
        let maxDateEnd = new Date();
        maxDateEnd.setDate(maxDateEnd.getDate() + 2);
        $('#end').daterangepicker({
            "autoUpdateInput": false,
            "singleDatePicker": true,
            "timePicker": true,
            "timePicker24Hour": true,
            "timePickerIncrement": 15,
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "{{ __('datetimepicker.applyLabel') }}",
                "cancelLabel": "{{ __('datetimepicker.cancelLabel') }}",
                "fromLabel": "{{ __('datetimepicker.fromLabel') }}",
                "toLabel": "{{ __('datetimepicker.toLabel') }}",
                "customRangeLabel": "{{ __('datetimepicker.customRangeLabel') }}",
                "weekLabel": "{{ __('datetimepicker.weekLabel') }}",
                "daysOfWeek": [
                    "{{ __('datetimepicker.daysOfWeek.Su') }}",
                    "{{ __('datetimepicker.daysOfWeek.Mo') }}",
                    "{{ __('datetimepicker.daysOfWeek.Tu') }}",
                    "{{ __('datetimepicker.daysOfWeek.We') }}",
                    "{{ __('datetimepicker.daysOfWeek.Th') }}",
                    "{{ __('datetimepicker.daysOfWeek.Fr') }}",
                    "{{ __('datetimepicker.daysOfWeek.Sa') }}"
                ],
                "monthNames": [
                    "{{ __('datetimepicker.monthNames.January') }}",
                    "{{ __('datetimepicker.monthNames.February') }}",
                    "{{ __('datetimepicker.monthNames.March') }}",
                    "{{ __('datetimepicker.monthNames.April') }}",
                    "{{ __('datetimepicker.monthNames.May') }}",
                    "{{ __('datetimepicker.monthNames.June') }}",
                    "{{ __('datetimepicker.monthNames.July') }}",
                    "{{ __('datetimepicker.monthNames.August') }}",
                    "{{ __('datetimepicker.monthNames.September') }}",
                    "{{ __('datetimepicker.monthNames.October') }}",
                    "{{ __('datetimepicker.monthNames.November') }}",
                    "{{ __('datetimepicker.monthNames.December') }}"
                ],
                "firstDay": 1
            },
            "minDate": minDateEnd,
            "maxDate": maxDateEnd,
            "showDropdowns": false,
            "opens": "center"
        }, function (start) {
            $('#hunting_end').val(start);
        });

        $('#end').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY H:mm'));
        });

        $('#end').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        $('.select2').select2()
    </script>
@stop
