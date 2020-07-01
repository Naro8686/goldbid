@extends('layouts.profile')
@section('page')
    <div class="right balance tabContent">
        <div class="title">Баланс</div>
        <p style="border-bottom: 3px solid #1B8BCB; margin: 0 10px;">
            Расскажу Вам несколько способов как пополнить баланс:<br>
            1. Купить пакет ставок<br>
            2. Попытаться выиграть пакет ставок в аукционе<br>
            3. Выиграть в аукционе любой товар где есть значек <img
                style="width: 20px; border-radius: 50%; border: 1px solid #1B8BCB;"
                title='Возможнось получить вместо выигранного товара "ставки"'
                src="{{asset('site/img/if_Update_984748.png')}}" alt=""> и обменять его на ставки<br>
            4. Бесплатно получить бонусы (ими как и ставками можно совершать ходы на аукционе)<br>
            Бонусы начисляются:<br>
            - за регистрацию<br>
            - за «приведённого друга» (реферальная программа)<br>
            - за положительный отзыв в социальных сетях<br>
            - за видеоотчет при получении выигранного товара<br>
        </p>
        <a href="{{route('site.coupon')}}">
            <button>Пополнить баланс</button>
        </a>
        <table class="table-balance">
            <tr>
                <th>Дата</th>
                <th>Ставок</th>
                <th>Бонусов</th>
                <th>Основание</th>
            </tr>
            @foreach($balance as $data)
                <tr>
                    <td style="white-space: nowrap;">{{$data->created_at}}</td>
                    <td>{{$data->bet}}</td>
                    <td>{{$data->bonus}}</td>
                    <td>{{$data->reason}}</td>
                </tr>
            @endforeach
        </table>
        <div>
            {!! $balance->links() !!}
        </div>
    </div>

@endsection
