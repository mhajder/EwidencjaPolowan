@if (session()->has('alerts_primary'))
    @foreach (session()->get('alerts_primary') as $alert)
        <x-adminlte-alert theme="primary" title="{{ $alert['title'] }}" dismissable>
            {{ $alert['message'] }}
        </x-adminlte-alert>
    @endforeach
@endif
@if (session()->has('alerts_success'))
    @foreach (session()->get('alerts_success') as $alert)
        <x-adminlte-alert theme="success" title="{{ $alert['title'] }}" dismissable>
            {{ $alert['message'] }}
        </x-adminlte-alert>
    @endforeach
@endif
@if (session()->has('alerts_warning'))
    @foreach (session()->get('alerts_warning') as $alert)
        <x-adminlte-alert theme="warning" title="{{ $alert['title'] }}" dismissable>
            {{ $alert['message'] }}
        </x-adminlte-alert>
    @endforeach
@endif
@if (session()->has('alerts_danger'))
    @foreach (session()->get('alerts_danger') as $alert)
        <x-adminlte-alert theme="danger" title="{{ $alert['title'] }}" dismissable>
            {{ $alert['message'] }}
        </x-adminlte-alert>
    @endforeach
@endif
