@extends('layouts.site')
@section('name-page')Аукцион@endsection
@push('css')
    <link href="{{asset('site/css/slick.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/auction.css')}}" rel="stylesheet">
@endpush
@section('content')
    <div class="main">
        <div class="container">
            <div class="card">
                <div class="left" style="overflow: hidden;">
                    @if($auction['error'])
                        <div>
                            <img src="{{asset('site/img/settings/error.jpg')}}" width="100%" alt="error">
                        </div>
                    @else
                        <div class="slider-for">
                            @foreach($auction['images'] as $image)
                                <div>
                                    <img src="{{asset($image['img'])}}" class="slide-img" alt="{{$image['alt']}}">
                                </div>
                            @endforeach
                        </div>
                        <div class="slider-nav">
                            @foreach($auction['images'] as $image)
                                <div>
                                    <img src="{{asset($image['img'])}}" class="slide-img" alt="{{$image['alt']}}">
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
                <div id="auction_page" data-auction-id="{{$auction['id']}}" class="dashboard"
                     @if($auction['error']) style="background-color: #FF001A" @endif>
                    @include('site.include.info')
                </div>
            </div>


            <div class="items" style="margin:40px 0">
                <div class="item active" id="description">Описаниие</div>
                <div class="item" id="specifications">Характеристики</div>
                <div class="item" id="services">Условия аукциона</div>
                <div class="item-text">
                    <div id="adescription">{!! $auction['desc'] !!}</div>
                    <div id="aspecifications">{!! $auction['specify'] !!}</div>
                    <div id="aservices">{!! $auction['terms'] !!}</div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                countdown($("[data-auction-id='{{$auction['id']}}']"));
                $("#description").click(function () {
                    $("#adescription").fadeIn();
                    $("#aspecifications, #aservices").hide();
                    $("#specifications, #services").removeClass("active");
                    $("#description").addClass("active");
                });
                $("#specifications").click(function () {
                    $("#aspecifications").fadeIn();
                    $("#adescription, #aservices").hide();
                    $("#description, #services").removeClass("active");
                    $("#specifications").addClass("active");
                });
                $("#services").click(function () {
                    $("#aservices").fadeIn();
                    $("#aspecifications, #adescription").hide();
                    $("#specifications, #description").removeClass("active");
                    $("#services").addClass("active");
                });
            });
        </script>
    @endpush
@endsection
