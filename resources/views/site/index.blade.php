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
            @include('site.auctions')
        </div>
    </div>
    @push('js')
        <script>
            // Echo.channel('goldbid_database_test-channel').listen('TestEvent', (e) => {
            //     alert('ok');
            //     console.log(e);
            // });
            // $(document).ready(function () {
            //     $('.bid.waiting').on('click',function (e) {
            //         $.get('/test');
            //     })
            // });
            // $(".overflow-alert").on("click", function () {
            //     $(".modal-alert").addClass("none");
            //     $(".overflow-alert").addClass("none");
            // });

        </script>
    @endpush
@endsection
