@extends('layouts.site')

@push('css')
    <link rel="stylesheet" href="{{asset('site/css/style.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container urinfo"><br>
        {!! $page->content !!}
        </div>
    </div>
@endsection
