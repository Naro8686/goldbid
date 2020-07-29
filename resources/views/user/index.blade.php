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
@endsection

