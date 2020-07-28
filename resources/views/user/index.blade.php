@extends('layouts.profile')
@section('page')
    <div class="right personal tabContent" style="font-size:14px;">
        <div class="title">Мой профиль</div>
        @if (session('status'))
            <b style="color: green">
                {{ session('status') }}
            </b>
        @endif

        <form class="content w-100" method="POST" action="{{route('profile.index')}}" style="margin-top: 50px">
            @csrf
            <div class="personal-info">
                <div class="personal-desc">
                    <p>Дата регистрации</p>
                </div>
                <div class="personal-edit">
                    <label class="personal-item">
                        <span class="personal-item-title">{{$user->created_at->format('Y-m-d')}}</span>
                    </label>
                </div>
            </div>
            <div class="personal-info">
                <div class="personal-desc">
                    <p>Изменение пароля</p>
                </div>
                <div class="personal-edit">
                    <label class="personal-item" for="current_password">
                        <span class="personal-item-title">Старый пароль:</span>
                        <input class="@error('current_password')is-invalid @enderror" required type="password"
                               name="current_password" id="current_password">
                    </label>
                    @error('current_password')
                    <small style="color: red;width: 100%;text-align: center;">
                        {{$message}}
                    </small>
                    @enderror
                    <label class="personal-item" for="new_password">
                        <span class="personal-item-title">Новый пароль:</span>
                        <input class="@error('new_password')is-invalid @enderror" required type="password"
                               name="new_password" id="new_password" placeholder="Не менее 8 символов">
                    </label>
                    @error('new_password')
                    <small style="color: red;width: 100%;text-align: center;">
                        {{$message}}
                    </small>
                    @enderror
                    <label class="personal-item" for="new_confirm_password">
                        <span class="personal-item-title">Подтвердите пароль:</span>
                        <input class="@error('new_confirm_password')is-invalid @enderror" required type="password"
                               name="new_confirm_password" id="new_confirm_password">
                    </label>
                    @error('new_confirm_password')
                    <small style="color: red;width: 100%;text-align: center;">
                        {{$message}}
                    </small>
                    @enderror
                    <label class="personal-item">
                        <span class="personal-item-title"></span>
                        <button class="button__app" type="submit">Сохранить</button>
                    </label>
                </div>
            </div>
            <div class="personal-info">
                <div class="personal-desc">
                    <p>Управление рассылками</p>
                </div>
                <div class="personal-edit">
                    <label class="personal-item">
                        <span class="personal-item-title">уведомлять по e-mail:</span>
                    </label>
                    @foreach($mailings as $mailing)
                        <label class="personal-item">
                            <span class="personal-item-title"> {{$mailing->title}}:</span>
                            <input type="checkbox"
                                   onclick='oNoFF("{{route('profile.subscribe',$mailing->id)}}",{subscribe:+($(this).prop("checked")),},"POST")'
                                   @if($user->subscribe->where('id',$mailing->id)->first()) checked
                                   @endif name="subscribe" value="{{$mailing->id}}">
                        </label>
                    @endforeach

                </div>
            </div>
        </form>

    </div>
    {{--    <div class="right tabContent">--}}
    {{--        <div class="title">Мой профиль</div>--}}


    {{--        <form style="display: flex" class="content" action="{{route('profile.index')}}" method="post"--}}
    {{--              enctype="multipart/form-data">--}}
    {{--            @csrf--}}
    {{--            <div class="one"><p>Аватар профиля:</p></div>--}}
    {{--            <div class="two">--}}
    {{--                <label for="upload-file" class="upload">--}}
    {{--                    <img src="{{asset('site/img/upload.png')}}" alt="">Загрузить--}}
    {{--                </label>--}}
    {{--            </div>--}}
    {{--            <input id="upload-file" class="upload" name="file" type="file">--}}
    {{--            <button style="height: 29px; margin-left: 10px;" class="save" type="submit">Сохранить</button>--}}
    {{--        </form>--}}
    {{--        <small style="color: red" class="one">--}}
    {{--            @error('file')--}}
    {{--            {{$message}}--}}
    {{--            @enderror--}}
    {{--        </small>--}}
    {{--        <form class="content" action="{{route('profile.index')}}" method="post">--}}
    {{--            @csrf--}}
    {{--            <div>--}}
    {{--                <div class="one"><p>Дата регистрации:</p></div>--}}
    {{--                <div class="two"><p class="data">{{$user->created_at->format('Y-m-d')}}</p>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div style="flex-direction: column;     align-items: flex-start;">--}}
    {{--                <label for="current_password" class="two">Старый пароль:--}}
    {{--                    <input required type="password" id="current_password" name="current_password">--}}
    {{--                </label>--}}
    {{--                <label for="new_password" class="two">Новый пароль:--}}
    {{--                    <input required type="password" id="new_password" name="new_password">--}}
    {{--                </label>--}}
    {{--                <label for="new_confirm_password" class="two">Подтвердите пароль:--}}
    {{--                    <input required type="password" id="new_confirm_password" name="new_confirm_password">--}}
    {{--                </label>--}}
    {{--                <small style="color: red" class="one">--}}
    {{--                    @error('current_password')--}}
    {{--                    {{$message}} <br>--}}
    {{--                    @enderror--}}
    {{--                    @error('new_password')--}}
    {{--                    {{$message}} <br>--}}
    {{--                    @enderror--}}
    {{--                    @error('new_confirm_password')--}}
    {{--                    {{$message}} <br>--}}
    {{--                    @enderror--}}
    {{--                </small>--}}
    {{--            </div>--}}
    {{--            <button class="save" type="submit">Сохранить</button>--}}
    {{--        </form>--}}
    {{--        <br>--}}
    {{--        @if (session('status'))--}}
    {{--            <small style="color: green" class="one">--}}
    {{--                {{ session('status') }}--}}
    {{--            </small>--}}
    {{--        @endif--}}

    {{--        <div class="subscribe">--}}
    {{--            <div style="font-size: 17px">Управление рассылками</div>--}}
    {{--            @foreach($mailings as $mailing)--}}
    {{--                <div style="font-size: 14px;margin: 3px 0;">--}}
    {{--                    <label>--}}
    {{--                        <input type="checkbox"--}}
    {{--                               onclick='oNoFF("{{route('profile.subscribe',$mailing->id)}}",{subscribe:+($(this).prop("checked")),},"POST")'--}}
    {{--                               @if($user->subscribe->where('id',$mailing->id)->first()) checked--}}
    {{--                               @endif name="subscribe" value="{{$mailing->id}}">--}}
    {{--                        {{$mailing->title}}--}}
    {{--                    </label>--}}
    {{--                </div>--}}
    {{--            @endforeach--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection

