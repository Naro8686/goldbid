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
    <link href="{{asset('site/css/preloader.css')}}" rel="stylesheet">
@endpush
@section('content')
    @include('site.include.svg_loader')
    <div class="auction container">
        <div class="favorite"></div>
        <div id="home_page" class="delete-margin">
            {{--            @include('site.include.auctions')--}}
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        let hashChanged = false;
        $(document).ready(function () {
            hashChanged = true;
            let page = parseInt(window.location.hash.replace('#', ''));
            if (isNaN(page) || page <= 0) page = 1;
            loadAuctions(page);
        });
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            hashChanged = true;
            let page = $(this).attr('href').split('page=')[1];
            loadAuctions(page);
        });
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                let page = window.location.hash.replace('#', '');
                if (hashChanged) hashChanged = false;
                else loadAuctions(page);
            }
        });
    </script>
@endpush
