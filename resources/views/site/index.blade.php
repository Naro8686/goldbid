@extends('layouts.site')
@section('slider')
    <div class="slaider">
        @foreach($sliders as $slider)
            <div class="slider____item">
                <img src="{{asset($slider->image)}}" alt="{{$slider->alt}}">
            </div>
        @endforeach
    </div>
@endsection
@section('name-page')Аукцион@endsection
@push('css')
    <link href="{{asset('site/css/slick.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/style.css')}}" rel="stylesheet">
@endpush
@section('content')
    <div class="auction container">
        <div class="favorite"></div>
        <div class="delete-margin">
            @include('site.include.auctions')
        </div>
    </div>
@endsection
