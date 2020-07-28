@extends('layouts.profile')
@section('page')
    <div class="right balance tabContent">
        <div class="title">Баланс</div>
        <table class="table-balance">
            <tr>
                <th>Время зачисления</th>
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
        <div class="page">
            {!! $balance->links() !!}
        </div>
    </div>
@endsection

