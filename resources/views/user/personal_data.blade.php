@extends('layouts.profile')
@section('page')

    <div class="right personal tabContent" style="font-size:14px;">
        <div class="title">Персональные данные</div>
        <div
            style="color: #1b8bcb;text-transform: uppercase;margin: 10px;width: 100%;font-size: 12px;font-weight: 600;display: block;justify-content: inherit;text-align: center">
            Заполните все поля регистрационнной анкеты. Не вводите фиктивные данные.
        </div>
        @if (session('status'))
            <b style="color: green">
                {{ session('status') }}
            </b>
        @endif
        <form class="content w-100" method="POST" action="{{route('profile.personal')}}">
            @csrf
            <div class="personal-info">
                <div class="personal-desc">
                    <p>Персональная информация *</p>
                </div>
                <div class="personal-edit">
                    <label class="personal-item" for="lname">
                        <span class="personal-item-title">Фамилия:</span>
                        <input type="text" class="@error('lname')is-invalid @enderror"
                               name="lname" value="{{old('lname')??$user->lname}}"
                               id="lname">
                    </label>

                    <label class="personal-item" for="fname">
                        <span class="personal-item-title">Имя:</span>
                        <input type="text" class="@error('fname')is-invalid @enderror"
                               value="{{old('fname')??$user->fname}}"
                               name="fname" id="fname">
                    </label>

                    <label class="personal-item" for="mname">
                        <span class="personal-item-title">Отчество:</span>
                        <input type="text" class="@error('mname')is-invalid @enderror"
                               value="{{old('mname')??$user->mname}}"
                               name="mname" id="mname">
                    </label>
                    <div class="personal-item">
                        <label>Пол:</label>
                        <div>
                            <label for="male" class="w-5" style="padding: 0 5px;">
                                <input name="gender" type="radio" value="male" id="male"
                                       @if(old('gender')==='male' || $user->gender === 'male') checked @endif>Мужской
                            </label>
                            <label for="female" class="w-5" style="padding: 0 5px;">
                                <input name="gender" type="radio" value="female" id="female"
                                       @if(old('gender')==='female' || $user->gender === 'female') checked @endif>Женский
                            </label>
                        </div>
                    </div>
                    <label class="personal-item" for="birthday">
                        <span class="personal-item-title">Дата рождения:</span>
                        <input type="date" class="@error('birthday')is-invalid @enderror"
                               value="{{old('birthday')??($user->birthday?$user->birthday->format('Y-m-d'):'')}}"
                               name="birthday" id="birthday">
                    </label>
                </div>
            </div>
            <div class="personal-info">
                <div class="personal-desc">
                    <p>Адрес доставки *</p>
                </div>
                <div class="personal-edit">
                    <label class="personal-item" for="postcode">
                        <span class="personal-item-title">Почтовый индекс:</span>
                        <input type="text" class="@error('postcode')is-invalid @enderror"
                               name="postcode" value="{{old('postcode')??$user->postcode}}"
                               id="postcode">
                    </label>
                    <label class="personal-item" for="region">
                        <span class="personal-item-title">Край/Область:</span>
                        <input type="text" class="@error('region')is-invalid @enderror"
                               value="{{old('region')??$user->region}}" name="region"
                               id="region">
                    </label>
                    <label class="personal-item" for="city">
                        <span class="personal-item-title">Город:</span>
                        <input type="text" class="@error('city')is-invalid @enderror"
                               value="{{old('city')??$user->city}}"
                               name="city" id="city">
                    </label>
                    <label class="personal-item" for="street">
                        <span class="personal-item-title">Улица, дом/кв:</span>
                        <input type="text" class="@error('street')is-invalid @enderror"
                               value="{{old('street')??$user->street}}"
                               name="street" id="street">
                    </label>
                </div>
            </div>
            <div class="personal-info">
                <div class="personal-desc">
                    <p>Электронная почта *</p>
                </div>
                <div class="personal-edit">
                    @if($user->email_code_verified && $user->email)
                        <label class="personal-item">
                            <span class="personal-item-title"></span>
                            <span>{{$user->email}}</span>
                        </label>
                    @else
                        @if($user->email && !$user->email_code_verified)
                            <p>
                                Подтверждение электронной почты
                            </p>
                        @endif
                        <label class="personal-item" for="email">
                            <span class="personal-item-title">Введите адрес:</span>
                            <input type="email" class="@error('email')is-invalid @enderror"
                                   name="email" id="email"
                                   placeholder="name@example.com"
                                   value="{{old('email')??$user->email}}">
                        </label>
                        @if($user->email && !$user->email_code_verified)
                            <div>
                                <p>На этот адрес мы отправим код</p>
                                <a href="{{route('profile.email_code_confirm')}}" class="button__app">Получить код:</a>
                            </div>
                            <p style="color: #1B8BCB" class="w-100">если письмо не пришло , проверьте папку спам </p>
                            <label class="personal-item" for="code">
                                <span class="personal-item-title">Введите код:</span>
                                <input type="text" class="@error('code')is-invalid @enderror"
                                       value="{{old('code')}}"
                                       id="code">
                                <button class="button__app" id="check__code" type="button">Подтвердить</button>
                            </label>
                        @endif
                    @endif


                </div>
            </div>
            <div class="personal-info">
                <div class="personal-desc">
                    <p>Платежные реквизиты для получения денежных переводов <br><small
                            style="color: #757575;font-weight: normal">(Можно заполнить позже )</small></p>
                </div>
                <div class="personal-edit">
                    <p>
                        Подтверждение электронной почты
                    </p>
                    <label class="personal-item" for="payment_type">
                        <span class="personal-item-title">Платежная система:</span>
                        <select name="payment_type" id="payment_type">
                            @foreach($payments as $type)
                                <option @if($user->payment_type === $type['id'])selected
                                        @endif value="{{$type['id']}}">{{$type['value']}}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="personal-item" for="ccnum">
                        <span class="personal-item-title">&#8470; карты или счета:</span>
                        <input type="text" class="@error('ccnum')is-invalid @enderror" name="ccnum" id="ccnum"
                               value="{{old('ccnum')??$user->ccnum}}">
                    </label>
                </div>

            </div>

            <div class="personal-info">
                <div class="personal-desc"></div>
                <div class="personal-edit">
                    <label class="personal-item">
                        <span class="personal-item-title"></span>
                        <button class="button__app" type="submit">Сохранить</button>
                    </label>
                </div>

            </div>
        </form>
        <form id="send__code_check" action="{{route('profile.email_code_confirm')}}" method="post"
              style="display: none">
            @csrf
            <input type="hidden" value="" name="code">
        </form>
    </div>
    @push('js')
        <script>
            $(document).ready(function () {
                $('#check__code').click(function () {
                    let code = $(this).prev().val();
                    let form = $('form#send__code_check');
                    let input = form.find('input[name="code"]');
                    if (code !== '' && input.val(code)) form.submit();
                });
            });
        </script>
    @endpush
@endsection

