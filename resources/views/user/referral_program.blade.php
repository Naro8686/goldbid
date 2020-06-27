@extends('layouts.profile')
@section('page')
    <div class="right referal tabContent">
        <div class="title">Реферальная программа</div>
        <p style=" border-bottom: 3px solid #1B8BCB; margin: 0 10px;">
				<span style="font-weight: bold; font-size: 14px;">Получайте бонусы по нашей реферальной программе, используйте их в аукционах
				и выигрывайте все что Вам нравиться.</span><br><br>
            Чтобы получить реферальную ссылку вы должны пройти <a href="{{route('profile.personal')}}"
                                                                  style="color: #1B8BCB; font-weight: bold;">полную
                регистрацию</a>
            и приобрести любой <a href="{{route('site.coupon')}}"
                                  style="color: #1B8BCB; font-weight: bold;">пакет ставок</a>. После этого
            разместите ее на своей странице в
            социальных сетях, на различных форумах, сайтах или просто отправьте своему другу.
            Он должен будет вставить эту ссылку в адресную строку браузера, пройти <a
                href="http://goldbid.ru/cabinet.php?str=2" style="color: #1B8BCB; font-weight: bold;">полную
                регистрацию</a> на нашем сайте и приобрести любой <a href="{{route('site.coupon')}}"
                                                                     style="color: #1B8BCB; font-weight: bold;">пакет
                ставок</a>. После этого в
            течение 3-х суток Вам будут зачислены бонусы в размере 50% от количества ставок
            купленных Вашим рефералом.
        </p>
        <div class="list-ref">
            <p>Что бы получить реферальную ссылку необходимо: </p>
            <ol style="list-style: dodgerblue;" type="1">
                @if(!$user->fullProfile())
                    <li>
                        <a href="{{route('profile.personal')}}" style="color: #1B8BCB;">
                            Заполнить свои персональные данные и номер телефона
                        </a>
                    </li>
                @endif
                @if (count([]) == 0)
                    <li>
                        <a href="{{route('site.coupon')}}" style="color: #1B8BCB; ">
                            Приобрести любой пакет ставок
                        </a>
                    </li>
                @endif
            </ol>

        </div>
        @if($user->fullProfile())
            <b style="margin-top: 15px;">Реферальная ссылка:
                <input id="cont" class="ref-link" style="padding: 4px; font-weight: normal;" type="text"
                       value="{{route('site.home',['ref'=>$user->id])}}">
                <input id="userButton" style="margin-left: 15px; border-radius: 20px; padding: 5px; outline: none;"
                       type="submit" value="КОПИРОВАТЬ">
            </b>
            <table>
                <tr>
                    <th>Дата регистрации</th>
                    <th>Имя реферала</th>
                    <th>Бонусов</th>
                </tr>
                @foreach($referral as $data)
                    <tr>
                        <td>{{$data->created_at}}</td>
                        <td>{{$data->nickname}}</td>
                        <td>ref_bonus</td>
                    </tr>
                @endforeach
            </table>
        @endif
    </div>
@endsection
