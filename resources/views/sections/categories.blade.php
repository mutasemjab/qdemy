@extends('layouts.front')

@section('content')
<div class="subcategories" style="display: block;">
    @include('includes.back-button', ['route' => 'dashboard'])
    @include('includes.section-title', ['title' => $categoryTitle])
    @include('includes.subcategories-grid')
</div>
@endsection