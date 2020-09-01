@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('admin.bots.index')}}" class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                    <span class="text">назад</span>
                </a>
            </div>
        </div>
        <form action="{{route('admin.bots.update',[$bot->id])}}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="time_to_bet">Время для ставки</label>
                <input type="text" class="form-control @error('time_to_bet') is-invalid @enderror"
                       id="time_to_bet"
                       pattern="^(\d+)(-(\d+))?$"
                       placeholder="0-1"
                       name="time_to_bet"
                       value="{{old('time_to_bet')??$bot->time_to_bet}}">
                @error('time_to_bet') <small class="text-danger">{{$message}}</small> @enderror
            </div>
            @if($bot->number === 1)
                <div class="form-group">
                    <label for="change_name">Менять имя</label>
                    <input type="text" class="form-control @error('change_name') is-invalid @enderror"
                           id="change_name"
                           pattern="^(\d+)-(\d+)$"
                           placeholder="0-1"
                           name="change_name"
                           value="{{old('change_name')??$bot->change_name}}">
                    @error('change_name') <small class="text-danger">{{$message}}</small> @enderror
                </div>
            @else
                <div class="form-group">
                    <label for="num_moves">Количество ходов</label>
                    <input type="text" class="form-control @error('num_moves') is-invalid @enderror"
                           id="num_moves"
                           pattern="^(\d+)-(\d+)$"
                           placeholder="0-1"
                           name="num_moves"
                           value="{{old('num_moves')??$bot->num_moves}}">
                    @error('num_moves') <small class="text-danger">{{$message}}</small> @enderror
                </div>
                <div class="form-group">
                    <label for="num_moves_other_bot">Количество ходов с другим ботом</label>
                    <input type="text" class="form-control @error('num_moves_other_bot') is-invalid @enderror"
                           id="num_moves_other_bot"
                           pattern="^(\d+)-(\d+)$"
                           placeholder="0-1"
                           name="num_moves_other_bot"
                           value="{{old('num_moves_other_bot')??$bot->num_moves_other_bot}}">
                    @error('num_moves_other_bot') <small class="text-danger">{{$message}}</small> @enderror
                </div>
            @endif
            <div>
                <button class="btn btn-block btn-outline-success">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
