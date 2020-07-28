@extends('layouts.profile')
@section('page')
    <div class="right referal tabContent">
        <div class="title">Реферальная программа</div>
        <p style="text-align: center;font-weight: bold; ">
            Получайте бонусы по нашей реферальной программе, используйте их в аукционах
            и выигрывайте все что Вам нравиться.</p>
        @if($user->fullProfile() && $user->couponOrder()->count())
            <p style="border-bottom: 3px solid #1B8BCB; margin:10px;">
                Разместите эту ссылку на своей странице в
                социальных сетях, на различных форумах, сайтах или просто отправьте своему другу.
                Он должен будет вставить эту ссылку в адресную строку браузера и зайти к нам на сайт.
                Затем пройти полную регистрацию, заполнить свои персональные данные и приобрести любой пакет
                ставок. После этого Вам будут зачислены Бонусы в размере 50%
                от количества Ставок купленных Вашим рефералом в первый раз .
            </p>
            <b style="margin-top: 15px;">Реферальная ссылка:
                <input id="ref-link" class="ref-link" style="padding: 5px; font-weight: normal;" type="text"
                       value="{{route('site.home',['ref'=>$user->id])}}">
                <input id="copyButton" class="button__app" style="margin-left: 10px;"
                       type="button" value="КОПИРОВАТЬ">
            </b>
            <table>
                <tr>
                    <th>Время зачисление</th>
                    <th>Имя реферала</th>
                    <th>Бонусов</th>
                </tr>
                @foreach($referrals as $referral)
                    <tr>
                        <td>{{$referral->pivot->updated_at}}</td>
                        <td>{{$referral->nickname}}</td>
                        <td>{{$referral->pivot->referral_bonus}}</td>
                    </tr>
                @endforeach
            </table>
            <div class="page">
                {!! $referrals->links() !!}
            </div>
        @else
            <div class="list-ref">
                <p>Что бы получить реферальную ссылку необходимо заполнить свои
                    <a href="{{route('profile.personal')}}" style="color: #1B8BCB;">персональные данные</a>
                    помеченные знаком * и приобрести любой
                    <a href="{{route('site.coupon')}}" style="color: #1B8BCB; ">пакет ставок</a>.
                </p>
            </div>
        @endif
    </div>
    @push('js')
        <script>
            document.getElementById("copyButton").addEventListener("click", function () {
                copyToClipboard(document.getElementById("ref-link"));
            });
        </script>
    @endpush
@endsection
