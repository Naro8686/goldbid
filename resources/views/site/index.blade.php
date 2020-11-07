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
    <div class="auction container">
        <div class="favorite"></div>
        <div id="home_page" class="delete-margin">
            <div class="preload__container">
                <div class="loader">
                    <div></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                    <defs>
                        <filter id="goo">
                            <fegaussianblur in="SourceGraphic" stddeviation="15" result="blur"></fegaussianblur>
                            <fecolormatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 30 -10" result="goo"></fecolormatrix>
                            <feblend in="SourceGraphic" in2="goo"></feblend>
                        </filter>
                    </defs>
                </svg>
            </div>
            {{--            @include('site.include.auctions')--}}
        </div>
    </div>
@endsection
@push('js')
    <script type="text/javascript">
        let click = false;
        $(document).ready(function () {
            let page = parseInt(window.location.hash.replace('#', ''));
            if (isNaN(page) || page <= 0) page = 1;
            loadAuctions(page);
        });
        $(document).on('click', '.pagination a', function (event) {
            event.preventDefault();
            click = true;
            let page = $(this).attr('href').split('page=')[1];
            loadAuctions(page);
        });
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                let page = window.location.hash.replace('#', '');
                if (click) click = !click;
                else loadAuctions(page);
            }
        });
    </script>
@endpush
