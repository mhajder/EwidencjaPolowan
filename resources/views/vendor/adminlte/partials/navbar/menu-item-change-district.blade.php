@php
    $districts = \App\Models\District::whereNull('parent_id')->get();
    $userSelectedDistrict = $districts->find(Auth::user()->selected_district);
@endphp
@if(sizeof($districts) >= 1)
<li class="nav-item dropdown ">
    <a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="true">
        <i class="fas fa-fw fa-flag"></i>
        {{ $userSelectedDistrict->name }}
    </a>
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
</li>
@endif
