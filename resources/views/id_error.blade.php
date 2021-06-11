@extends('adminlte::page')

@section('title', $error_title)

@section('content_header')
    <h1>{{ $error_title }}</h1>
@stop

@section('content')

    <div class="alert alert-danger">
        <h4><i class="icon fa fa-check"></i> Błąd!</h4>
        {{ $error_message }}
    </div>

@stop
