@extends('layouts.front')

@section('content')
<div class="files-section" style="display: block;">
    @if(isset($backRoute) && $backRoute == 'dashboard')
        @include('includes.back-button', ['route' => 'dashboard', 'text' => 'رجوع للرئيسية'])
    @elseif(isset($backRoute) && $backRoute == 'categories.subcategories')
        @include('includes.back-button', ['route' => 'categories.subcategories', 'params' => $backParams, 'text' => 'رجوع'])
    @else
        @include('includes.back-button', ['route' => 'categories.show', 'params' => [$type], 'text' => 'رجوع'])
    @endif

    @include('includes.section-title', ['title' => $categoryTitle . ' - الملفات'])
    
    @if($files->count() > 0)
        @include('includes.files-grid')
    @else
        @include('includes.empty-state', ['message' => 'لا توجد ملفات', 'description' => 'هذه الفئة لا تحتوي على ملفات حالياً'])
    @endif
</div>
@endsection