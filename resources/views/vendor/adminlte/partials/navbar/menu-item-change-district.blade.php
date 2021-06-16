@php
    $districts = \App\Models\District::whereNull('parent_id')->get();
@endphp
@if(count($districts) >= 1)
<li class="nav-item dropdown ">
    <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="true">
        <i class="fas fa-fw fa-flag"></i>
        {{ Auth::user()->selectedDistrict->name }}
    </a>
    @if(count($districts) > 1)
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{ __('adminlte::menu.change_district') }}</span>
        @foreach ($districts as $district)
            @if($district->id != Auth::user()->selected_district)
                <div class="dropdown-divider"></div>
                <a href="{{ route('district.change', ['district_id' => $district->id ]) }}" class="dropdown-item text-wrap">
                    {{ $district->name }}
                    <span class="float-right text-muted text-sm">{{ $district->code }}</span>
                </a>
            @endif
        @endforeach
    @endif
</li>
@endif
