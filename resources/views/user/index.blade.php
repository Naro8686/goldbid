@extends('layouts.profile')
@section('page')

    <div class="right tabContent">
        <div class="title">Мой профиль</div>


        <form style="display: flex" class="content" action="{{route('profile.index')}}" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="one"><p>Аватар профиля:</p></div>
            <div class="two">
                <label for="upload-file" class="upload">
                    <img src="{{asset('site/img/upload.png')}}" alt="">Загрузить
                </label>
            </div>
            <input id="upload-file" class="upload" name="file" type="file">
            <button style="height: 29px; margin-left: 10px;" class="save" type="submit">Сохранить</button>
        </form>
        <small style="color: red" class="one">
            @error('file')
            {{$message}}
            @enderror
        </small>
        <form class="content" action="{{route('profile.index')}}" method="post">
            @csrf
            <div>
                <div class="one"><p>Дата регистрации:</p></div>
                <div class="two"><p class="data">{{$user->created_at->format('Y-m-d')}}</p>
                </div>
            </div>
            <div style="flex-direction: column;     align-items: flex-start;">
                <label for="current_password" class="two">Старый пароль:
                    <input required type="password" id="current_password" name="current_password">
                </label>
                <label for="new_password" class="two">Новый пароль:
                    <input required type="password" id="new_password" name="new_password">
                </label>
                <label for="new_confirm_password" class="two">Подтвердите пароль:
                    <input required type="password" id="new_confirm_password" name="new_confirm_password">
                </label>
                <small style="color: red" class="one">
                    @error('current_password')
                    {{$message}} <br>
                    @enderror
                    @error('new_password')
                    {{$message}} <br>
                    @enderror
                    @error('new_confirm_password')
                    {{$message}} <br>
                    @enderror
                </small>
            </div>
            <button class="save" type="submit">Сохранить</button>
        </form>
        <br>
        @if (session('status'))
            <small style="color: green" class="one">
                {{ session('status') }}
            </small>
        @endif

        <div class="subscribe">
            <div style="font-size: 17px">Управление рассылками</div>
            @foreach($mailings as $mailing)
                <div style="font-size: 14px;margin: 3px 0;">
                    <label>
                        <input type="checkbox"
                               onclick='oNoFF("{{route('profile.subscribe',$mailing->id)}}",{subscribe:+($(this).prop("checked")),},"POST")'
                               @if($user->subscribe->where('id',$mailing->id)->first()) checked
                               @endif name="subscribe" value="{{$mailing->id}}">
                        {{$mailing->title}}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endsection

