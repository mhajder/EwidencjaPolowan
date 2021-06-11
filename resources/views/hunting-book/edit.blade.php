@extends('adminlte::page')

@section('title', 'Edycja polowania "' . $hunting->hunting_id)

@section('content_header')
    <h1>Edycja polowania "{{ $hunting->hunting_id }}" dla obwodu "{{ Auth::user()->selectedDistrict->name }}"</h1>
@stop

@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Zapisano!</h4>
            Dane zostały zapisane poprawnie.
        </div>
    @endif

    <form id="hunting_edit" role="form" action="{{ route('hunting.update', ['id' => $hunting->id]) }}" method="post">
    {!! csrf_field() !!}
    {{ method_field('patch') }}
    <!-- left column -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                    @php
                        $user = $hunting->user;
                        $huntingGrounds = $hunting->usedHuntingGrounds->pluck('code')->implode(', ');
                        $huntedAnimals = $hunting->huntedAnimals
                    @endphp
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunter_name">Myśliwy</label>
                                    <input type="text" class="form-control" id="hunter_name"
                                           value="{{ $user->first_name }} {{ $user->last_name }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_grounds">Rewir</label>
                                    <input type="text" class="form-control" id="hunting_grounds"
                                           value="{{ $huntingGrounds }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_start">Start</label>
                                    <input type="text" class="form-control" id="hunting_start"
                                           value="{{ $hunting->start }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_end">Koniec</label>
                                    <input type="text" class="form-control" id="hunting_end" value="{{ $hunting->end }}"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="hunting_shots">Ilość oddanych strzałów</label>
                                    <input type="number" min="0" max="100" class="form-control" id="hunting_shots"
                                           name="hunting_shots"
                                           @if (old('hunting_shots', null) != null) value="{{ old('hunting_shots') }}"
                                           @else value="{{ $hunting->shots }}" @endif required>
                                    @if ($errors->has('hunting_shots'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_shots') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div class="col-sm-3">
                                <a id="addHuntedAnimal" class="btn btn-success btn-block">Dodaj upolowaną zwierzynę</a>
                            </div>
                        </div>
                        <div class="row" style="padding-top: 15px">
                            <div class="col-sm-12">
                                <div style='overflow: auto; width: 100%; position: relative;'>
                                    <table id="huntedAnimalTable" class="table table-sm table-bordered responsive"
                                           style="display: none">
                                        <thead>
                                        <tr>
                                            <th>Kategoria</th>
                                            <th>Typ</th>
                                            <th>Przeznaczenie</th>
                                            <th>Znacznik tuszy</th>
                                            <th>Waga</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="huntedAnimalTbody">
                                        @foreach($huntedAnimals as $key => $value)
                                            <tr>
                                                <input type="hidden" name="animal_id_database[]"
                                                       value="{{ $value->id }}">
                                                <td>
                                                    <select
                                                        class="form-control animal_category_id {{ $errors->has('animal_category_id_stored.'.$key) ? 'is-invalid' : '' }}"
                                                        name="animal_category_id_stored[]" required>
                                                        <option value="">Wybierz kategorię</option>
                                                        @foreach ($animal_categories as $animal_category)
                                                            <option value="{{ $animal_category->id }}"
                                                                    @if (old('animal_category_id_stored.'.$key) == $animal_category->id) selected
                                                                    @elseif(old('animal_category_id_stored.'.$key, null) == null && $value->animal_category_id == $animal_category->id) selected @endif>{{ $animal_category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('animal_category_id_stored.'.$key))
                                                        <span class="invalid-feedback">
                                                            {{ $errors->first('animal_category_id_stored.'.$key) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select
                                                        class="form-control animal_id {{ $errors->has('animal_id_stored.'.$key) ? 'is-invalid' : '' }}"
                                                        name="animal_id_stored[]" required>
                                                        <option value="">Wybierz typ</option>
                                                        @foreach ($animals as $animal)
                                                            <option data-parent="{{ $animal->parent_id }}"
                                                                    value="{{ $animal->id }}"
                                                                    @if (old('animal_id_stored.'.$key) == $animal->id) selected
                                                                    @elseif(old('animal_id_stored.'.$key, null) == null && $value->animal_id == $animal->id) selected @endif>{{ $animal->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('animal_id_stored.'.$key))
                                                        <span class="invalid-feedback">
                                                            {{ $errors->first('animal_id_stored.'.$key) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select
                                                        class="form-control {{ $errors->has('purpose_stored.'.$key) ? 'is-invalid' : '' }}"
                                                        name="purpose_stored[]" required>
                                                        <option value="">Wybierz przeznaczenie</option>
                                                        @foreach(\App\Enums\HuntedAnimalPurposes::asSelectArray() as $keyHuntedAnimalPurposes => $valueHuntedAnimalPurposes)
                                                            <option value="{{ $keyHuntedAnimalPurposes }}"
                                                                    @if (old('purpose_stored.'.$key) == $keyHuntedAnimalPurposes) selected
                                                                    @elseif(old('purpose_stored.'.$key, null) == null && $value->purpose == $keyHuntedAnimalPurposes) selected @endif>{{ $valueHuntedAnimalPurposes }}</option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('purpose_stored.'.$key))
                                                        <span class="invalid-feedback">
                                                            {{ $errors->first('purpose_stored.'.$key) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text"
                                                           class="form-control {{ $errors->has('tag_stored.'.$key) ? 'is-invalid' : '' }}"
                                                           name="tag_stored[]" maxlength="100"
                                                           @if (old('tag_stored.'.$key, null) != null) value="{{ old('tag_stored.'.$key) }}"
                                                           @else value="{{ $value->tag }}" @endif required>
                                                    @if ($errors->has('tag_stored.'.$key))
                                                        <span class="invalid-feedback">
                                                             {{ $errors->first('tag_stored.'.$key) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number"
                                                           class="form-control {{ $errors->has('weight_stored.'.$key) ? 'is-invalid' : '' }}"
                                                           name="weight_stored[]" min="0"
                                                           @if (old('weight_stored.'.$key, null) != null) value="{{ old('weight_stored.'.$key) }}"
                                                           @else value="{{ $value->weight }}" @endif required>
                                                    @if ($errors->has('weight_stored.'.$key))
                                                        <span class="invalid-feedback">
                                                            {{ $errors->first('weight_stored.'.$key) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a id="removeHuntedAnimal" class="btn btn-danger btn-block"><i
                                                            class="fas fa-times"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(old('animal_id') != "")
                                            @foreach(old('animal_id') as $key => $value)
                                                <tr>
                                                    <td>
                                                        <select
                                                            class="form-control animal_category_id {{ $errors->has('animal_category_id.'.$key) ? 'is-invalid' : '' }}"
                                                            name="animal_category_id[]" required>
                                                            <option value="">Wybierz kategorię</option>
                                                            @foreach ($animal_categories as $animal_category)
                                                                <option value="{{ $animal_category->id }}"
                                                                        @if (old('animal_category_id.'.$key) == $animal_category->id) selected @endif>{{ $animal_category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('animal_category_id.'.$key))
                                                            <span class="invalid-feedback">
                                                                {{ $errors->first('animal_category_id.'.$key) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select
                                                            class="form-control animal_id {{ $errors->has('animal_id.'.$key) ? 'is-invalid' : '' }}"
                                                            name="animal_id[]" required>
                                                            <option value="">Wybierz typ</option>
                                                            @foreach ($animals as $animal)
                                                                <option data-parent="{{ $animal->parent_id }}"
                                                                        value="{{ $animal->id }}"
                                                                        @if (old('animal_id.'.$key) == $animal->id) selected @endif>{{ $animal->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('animal_id.'.$key))
                                                            <span class="invalid-feedback">
                                                                {{ $errors->first('animal_id.'.$key) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <select
                                                            class="form-control {{ $errors->has('purpose.'.$key) ? 'is-invalid' : '' }}"
                                                            name="purpose[]" required>
                                                            <option value="">Wybierz przeznaczenie</option>
                                                            @foreach(\App\Enums\HuntedAnimalPurposes::asSelectArray() as $keyHuntedAnimalPurposes => $valueHuntedAnimalPurposes)
                                                                <option value="{{ $keyHuntedAnimalPurposes }}"
                                                                        @if (old('purpose.'.$key) == $keyHuntedAnimalPurposes) selected @endif>{{ $valueHuntedAnimalPurposes }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('purpose.'.$key))
                                                            <span class="invalid-feedback">
                                                                {{ $errors->first('purpose.'.$key) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                               class="form-control {{ $errors->has('tag.'.$key) ? 'is-invalid' : '' }}"
                                                               name="tag[]" maxlength="100"
                                                               @if (old('tag.'.$key, null) != null) value="{{ old('tag.'.$key) }}"
                                                               @endif required>
                                                        @if ($errors->has('tag.'.$key))
                                                            <span class="invalid-feedback">
                                                                {{ $errors->first('tag.'.$key) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="number"
                                                               class="form-control {{ $errors->has('weight.'.$key) ? 'is-invalid' : '' }}"
                                                               name="weight[]" min="0"
                                                               @if (old('weight.'.$key, null) != null) value="{{ old('weight.'.$key) }}"
                                                               @endif required>
                                                        @if ($errors->has('weight.'.$key))
                                                            <span class="invalid-feedback">
                                                                {{ $errors->first('weight.'.$key) }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a id="removeHuntedAnimal" class="btn btn-danger btn-block"><i
                                                                class="fas fa-times"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="hunting_description">Opis</label>
                                    <textarea rows="3"
                                              class="form-control {{ $errors->has('hunting_description') ? 'is-invalid' : '' }}"
                                              name="hunting_description"
                                              id="hunting_description" placeholder="Opis"
                                              maxlength="500">@if (old('hunting_description', null) != null){{ old('hunting_description') }} @else{{ $hunting->description }}@endif</textarea>
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
                                <button type="submit" class="btn btn-primary btn-block">Zapisz</button>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </form>

@stop

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            const l = $('tbody#huntedAnimalTbody tr').length;
            if (l > 0) {
                $("#huntedAnimalTable").removeAttr("style")
            }
            $('body').on('click', '#addHuntedAnimal', function () {
                addRow();
                $("#huntedAnimalTable").removeAttr("style")
            });

            function addRow() {
                const addRow = `
                <tr>
                    <td>
                        <select class="form-control animal_category_id" name="animal_category_id[]" required id="city">
                        <option value="">Wybierz kategorię</option>
                        @foreach ($animal_categories as $animal_category)
                <option value="{{ $animal_category->id }}">{{ $animal_category->name }}</option>
                        @endforeach
                </select>
            </td>
            <td>
                <select class="form-control animal_id" name="animal_id[]" required id="street">
                <option value="">Wybierz typ</option>
@foreach ($animals as $animal)
                <option data-parent="{{ $animal->parent_id }}" value="{{ $animal->id }}">{{ $animal->name }}</option>
                        @endforeach
                </select>
            </td>
            <td>
                <select class="form-control" name="purpose[]" required>
                <option value="">Wybierz przeznaczenie</option>
@foreach(\App\Enums\HuntedAnimalPurposes::asSelectArray() as $keyHuntedAnimalPurposes => $valueHuntedAnimalPurposes)
                <option value="{{ $keyHuntedAnimalPurposes }}">{{ $valueHuntedAnimalPurposes }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="tag[]" maxlength="100" required>
            </td>
            <td>
                <input type="number" class="form-control" name="weight[]" min="0" required>
            </td>
            <td>
                <a id="removeHuntedAnimal" class="btn btn-danger btn-block"><i class="fas fa-times"></i></a>
            </td>
        </tr>`;
                $('#huntedAnimalTbody').append(addRow);

                $('select.animal_category_id').last().closest('tr').find('select.animal_id option').hide();
                $('select.animal_category_id').last().closest('tr').find('select.animal_id option[value=""]').show();
            }

            $('body').on('click', '#removeHuntedAnimal', function () {
                $(this).closest('tr').remove();
                const l = $('tbody#huntedAnimalTbody tr').length;
                if (l === 0) {
                    $("#huntedAnimalTable").css("display", "none");
                }
            });

            $('select.animal_category_id').each(function (index) {
                $(this).closest('tr').find('select.animal_id option').hide();
                $(this).closest('tr').find('select.animal_id option[value=""]').show();
                $(this).closest('tr').find('select.animal_id option[data-parent="' + $(this).val() + '"]').show();
            });

            $('body').on('change', 'select.animal_category_id', function () {
                $(this).closest('tr').find('select.animal_id option').hide();
                $(this).closest('tr').find('select.animal_id option[value=""]').show();
                $(this).closest('tr').find('select.animal_id option[data-parent="' + $(this).val() + '"]').show();

                if ($(this).closest('tr').find('select.animal_id option:selected').data('parent') !== $(this).val()) {
                    $(this).closest('tr').find('select.animal_id').val("");
                }
            });
        });
    </script>
@stop
