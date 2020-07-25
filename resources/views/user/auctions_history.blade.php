@extends('layouts.profile')
@section('page')
    <div class="right balance tabContent">
        <div class="title">История моих аукционов</div>
        <p style="font-size: 15px; border-bottom: 3px solid #1B8BCB; margin: 0 10px;">
            <span style="font-style: italic; ">"Человек, которому повезло,<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp это человек, который делал то, что другие только собирались сделать."</span><br><br>

            Жюль Ренар 1864г
        </p>
        <table class="history">
            <tr>
                <th>Дата</th>
                <th>Товар</th>
                <th>Ставок</th>
                <th>Бонусов</th>
                <th>Статус лота</th>
            </tr>
            @foreach($bids as $auction_id => $bid)
                <tr>
                    <td style="white-space: nowrap;">{{$bid['end']}}</td>
                    <td>
                        <a style="color: #1B8BCB;"
                           href="{{route('auction.index',$auction_id)}}">{{$bid['title']}}</a>
                    </td>
                    <td>{{$bid['bet']}}</td>
                    <td>{{$bid['bonus']}}</td>
                    <td>{{$bid['win']?'Победа':'Участие '}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

