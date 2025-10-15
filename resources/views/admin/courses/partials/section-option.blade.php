{{-- Recursive Section Option Partial for Form Dropdowns --}}
@php
    $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
    $prefix = $level > 0 ? '└─&nbsp;' : '';
    $icon = $level > 0 ? '📁' : '📂';
    $childSections = $allSections->where('parent_id', $section->id);
@endphp

<option value="{{ $section->id }}" 
        {{ $selectedId == $section->id ? 'selected' : '' }}
        data-level="{{ $level }}"
        style="{{ $level > 0 ? 'color: #6c757d;' : '' }}">
    {!! $indent !!}{!! $prefix !!}{{ $icon }} {{ $section->title_en }} - {{ $section->title_ar }}
    @if($childSections->count() > 0)
        ({{ $childSections->count() }} {{ __('messages.subsections') }})
    @endif
</option>

{{-- Recursively include child sections --}}
@foreach($childSections as $childSection)
    @include('admin.courses.partials.section-option', [
        'section' => $childSection,
        'allSections' => $allSections,
        'level' => $level + 1,
        'selectedId' => $selectedId
    ])
@endforeach