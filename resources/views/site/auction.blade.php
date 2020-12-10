@extends('layouts.site')
@section('name-page')Аукцион@endsection
@push('css')
    <link href="{{asset('site/css/slick.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/slick-theme.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/auction.css')}}" rel="stylesheet">
@endpush
@section('content')
    <div class="main">
        <div class="container" id="auction">
            <auction-page v-bind:auction="auction" v-bind:csrf="csrf"></auction-page>
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
                new Vue({
                    el: '#auction',
                    data: {
                        auction: {!! $auction !!},
                        csrf: "{{csrf_token()}}",
                    },
                });
                $('.items > .item').on('click', function () {
                    $('.items>div:not(.item-text)').removeClass('active');
                    $('.items>div.item-text>div').hide();
                    let btn = $(this);
                    let text = $(`#a${btn.attr('id')}`);
                    btn.addClass('active');
                    text.show();
                })
            });
        </script>
    @endpush
@endsection
