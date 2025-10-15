@extends('layouts.front')

@section('content')
<div class="lessons-section" style="display: block;">
    @if(isset($backRoute) && $backRoute == 'dashboard')
        @include('includes.back-button', ['route' => 'dashboard', 'text' => 'رجوع للرئيسية'])
    @elseif(isset($backRoute) && $backRoute == 'categories.subcategories')
        @include('includes.back-button', ['route' => 'categories.subcategories', 'params' => $backParams, 'text' => 'رجوع'])
    @else
        @include('includes.back-button', ['route' => 'categories.show', 'params' => [$type], 'text' => 'رجوع'])
    @endif

    @include('includes.section-title', ['title' => $categoryTitle . ' - الدروس'])
    
    @if($lessons->count() > 0)
        @include('includes.lessons-grid')
    @else
        @include('includes.empty-state', ['message' => 'لا توجد دروس', 'description' => 'هذه الفئة لا تحتوي على دروس حالياً'])
    @endif
</div>
@endsection