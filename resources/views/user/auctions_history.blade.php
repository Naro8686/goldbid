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

{{--            <tr>--}}
{{--                <td style="white-space: nowrap;">timebid</td>--}}
{{--                <td><a style="color: #1B8BCB;"--}}
{{--                       href="auction.php?id=">name</a></td>--}}
{{--                <td>bet</td>--}}
{{--                <td>bonus</td>--}}
{{--                <td>win</td>--}}
{{--            </tr>--}}
        </table>
    </div>
@endsection

