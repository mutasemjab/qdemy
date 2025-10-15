@extends('layouts.front')

@section('content')
<div class="subcategories" style="display: block;">
    @if(isset($backRoute) && $backRoute == 'dashboard')
        @include('includes.back-button', ['route' => 'dashboard', 'text' => 'رجوع للرئيسية'])
    @else
        @include('includes.back-button', ['route' => 'categories.show', 'params' => [$type], 'text' => 'رجوع'])
    @endif
    
    @include('includes.section-title', ['title' => $categoryTitle])
    
    @if($categories->count() > 0)
        @include('includes.subcategories-grid')
    @else
        @include('includes.empty-state', ['message' => 'لا توجد عناصر فرعية', 'description' => 'هذه الفئة فارغة حالياً'])
    @endif
</div>
@endsection