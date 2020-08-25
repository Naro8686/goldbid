@php($layouts = request()->segment(1) === 'admin' && (auth()->check() && auth()->user()->is_admin) ? 'layouts.admin':'layouts.site')
@extends($layouts)
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('site/css/error.css')}}">
@endpush
@section('content')
    <div class="error-container">
        <div class="text-center">
            <div class="error mx-auto" data-text="404">404</div>
            <p class="lead text-gray-800 mb-5">Страница не найдена</p>
        </div>
    </div>
@endsection
