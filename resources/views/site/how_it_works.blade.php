@extends('layouts.site')
@section('name-page')Как это работает@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/howitwork.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container">
            <div class="howitwork">
                @foreach($steps as $key => $step)
                    <div class="step w-100 d-flex @if($key%2) flex-row-reverse @else flex-row @endif">
                        <img class="bg" src="{{asset($step->image)}}" alt="{{$step->alt}}">
                    </div>
                @endforeach
            </div>
            <div class="accordeon">
                <h1>Часто задаваемы вопросы</h1>
                <ul>
                    @foreach($questions as $question)
                        <li>
                            <input type="checkbox" checked>
                            <i></i>
                            <h2>{{$question->title}}</h2>
                            <p>
                                {!! $question->description !!}
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection
