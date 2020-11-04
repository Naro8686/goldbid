@extends('layouts.profile')
@section('page')
    <div class="right balance tabContent">
        <div class="title">История моих аукционов</div>
        <table class="history">
            <tr>
                <th>Наименование</th>
                <th>Время завершения</th>
                <th>Ставки</th>
                <th>Бонусы</th>
                <th>Результат</th>
            </tr>
            @foreach($bids as $bid)
                {{$bid['auction_id']}}
                <tr>
                    <td>
                        <a style="color: #1B8BCB;"
                           href="{{route('auction.index',$bid['auction_id'])}}">{{$bid['title']}}</a>
                    </td>
                    <td style="white-space: nowrap;">{{$bid['end']}}</td>
                    <td>{{$bid['bet']}}</td>
                    <td>{{$bid['bonus']}}</td>
                    <td>{{((bool)$bid['wins'])?'Победа':'Участие '}}</td>
                </tr>
            @endforeach
        </table>
        <div class="page">
            {!! $bids->links() !!}
        </div>
    </div>
@endsection

