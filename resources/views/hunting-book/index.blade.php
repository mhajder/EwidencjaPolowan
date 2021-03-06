@extends('adminlte::page')

@section('title', 'Książka polowań')

@section('content_header')
    <h1>Książka polowań</h1>
@stop

@section('content')
    @include('partials.alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body">
                    <div style='overflow:auto; width:100%;position:relative;'>
                        <table id="hunting_book" class="table table-bordered table-hover responsive"
                               width="100%" role="grid">
                            <thead>
                            <tr>
                                <th>Lp.</th>
                                <th>Myśliwy</th>
                                <th>Upoważnienie</th>
                                <th>Rewir</th>
                                <th>Start</th>
                                <th>Koniec</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($huntings as $hunting)
                                <tr data-widget="expandable-table" aria-expanded="false">
                                    <td>{{ $hunting->hunting_id }}</td>
                                    <td>{{ $hunting->user->name }}</td>
                                    <td>{{ $hunting->authorization->number }}</td>
                                    <td>{{ $hunting->usedHuntingGrounds->pluck('code')->implode(', ') }}</td>
                                    <td>{{ $hunting->start->format('Y-m-d H:i') }}</td>
                                    <td>{{ $hunting->end->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @php
                                            $now = Carbon::now();
                                            $huntingStartDate = Carbon::parse($hunting->start);
                                            $huntingEndDate = Carbon::parse($hunting->end);
                                            $huntingMaxEditDate = Carbon::parse($hunting->end)->add(1, 'day')
                                        @endphp
                                        @if($hunting->user_id == Auth::user()->id && $huntingStartDate->greaterThan($now) && $hunting->canceled == 0)
                                            <form role="form"
                                                  action="{{ route('hunting.cancel', ['id' => $hunting->id]) }}"
                                                  method="post">
                                                {!! csrf_field() !!}
                                                {{ method_field('patch') }}
                                                <button class="btn btn-warning"><i class="fa fa-ban"></i> Odwołaj
                                                </button>
                                            </form>
                                        @endif
                                        @if($hunting->user_id != Auth::user()->id && $huntingStartDate->greaterThan($now) && $hunting->canceled == 0)
                                            <span class="btn btn-warning"><i class="fa fa-ban"></i> Polowanie zaplanowane</span>
                                        @endif
                                        @if($hunting->canceled == 1)
                                            <span class="btn btn-secondary"><i class="fa fa-ban"></i> Polowanie odwołane</span>
                                        @endif
                                        @if($hunting->user_id == Auth::user()->id && $huntingStartDate->lessThan($now) && $huntingEndDate->greaterThan($now) && $hunting->canceled == 0)
                                            <form role="form"
                                                  action="{{ route('hunting.finish', ['id' => $hunting->id]) }}"
                                                  method="post">
                                                {!! csrf_field() !!}
                                                {{ method_field('patch') }}
                                                <button class="btn btn-danger"><i class="fa fa-times"></i> Zakończ
                                                </button>
                                            </form>
                                        @endif
                                        @if($hunting->user_id == Auth::user()->id && $huntingEndDate->lessThan($now) && $huntingMaxEditDate->greaterThan($now) && $hunting->canceled == 0)
                                            <a class="btn btn-success"
                                               href="{{ route('hunting.edit', ['id' => $hunting->id ]) }}"><i
                                                    class="fa fa-edit"></i> Edytuj</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="expandable-body d-none">
                                    <td colspan="7">
                                        <div class="col-sm-12" style="display: none;">
                                            <b>Oddana liczba strzałów:</b> {{ $hunting->shots }}
                                        </div>
                                        @if(count($hunting->huntedAnimals) > 0)
                                            <div class="col-sm-12" style="display: none;">
                                                <b>Upolowane zwierzęta:</b>
                                                @foreach($hunting->huntedAnimals as $huntedAnimal)
                                                    <span class="btn btn-default btn-sm">{{ $animals[$huntedAnimal->animal_category_id - 1]['name'] }} - {{ $animals[$huntedAnimal->animal_id - 1]['name'] }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        @isset($hunting->description)
                                            <div class="col-sm-12" style="display: none;">
                                                <b>Opis:</b> {{ $hunting->description }}
                                            </div>
                                        @endisset
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    {{ $huntings->links() }}
                </div>

                <!-- /.card-body -->
            </div>
        </div>
    </div>
@stop
