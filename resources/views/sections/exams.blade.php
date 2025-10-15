{{-- sections/exams.blade.php --}}
@extends('layouts.front')

@section('content')
<div class="exams-section" style="display: block;">
    @if(isset($backRoute) && $backRoute == 'dashboard')
        @include('includes.back-button', ['route' => 'dashboard', 'text' => 'رجوع للرئيسية'])
    @elseif(isset($backRoute) && $backRoute == 'categories.subcategories')
        @include('includes.back-button', ['route' => 'categories.subcategories', 'params' => $backParams, 'text' => 'رجوع'])
    @else
        @include('includes.back-button', ['route' => 'categories.show', 'params' => ['exams'], 'text' => 'رجوع'])
    @endif

    @include('includes.section-title', ['title' => $categoryTitle . ' - الامتحانات'])
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    @if($exams->count() > 0)
        @include('includes.exams-grid')
    @else
        @include('includes.empty-state', ['message' => 'لا توجد امتحانات', 'description' => 'هذه الفئة لا تحتوي على امتحانات حالياً'])
    @endif
</div>
@endsection