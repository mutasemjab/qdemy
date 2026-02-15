@extends('layouts.app')

@section('title', 'الرئيسية')

@section('content')
    @include('web.alert-message')

    @include('web.home.hero')

    @include('web.home.hero-cards')

    @include('web.home.services')

    @include('web.home.teachers')

    @include('web.home.videos')

    @include('web.home.stats')

    @include('web.home.faq')

    @include('web.home.reviews')

    @include('web.home.blog')

@endsection
