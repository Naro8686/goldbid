@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Администратор</h6>
            </div>
            <div class="card-body">
                <form action="{{route('admin.profile')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label for="phone" class="col-sm-2 col-form-label">Логин</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control mask" id="phone" placeholder="телефон" name="phone" value="{{old('phone')??auth()->user()->phone}}">
                            @error('phone')
                            <small class="text-danger">
                                {{$message}}
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="current_password" class="col-sm-2 col-form-label">Старый пароль</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="current_password" name="current_password" placeholder="старый пароль">
                            @error('current_password')
                            <small class="text-danger">
                                {{$message}}
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="new_password" class="col-sm-2 col-form-label">Новый пароль</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="новый пароль">
                            @error('new_password')
                            <small class="text-danger">
                                {{$message}}
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="new_confirm_password" class="col-sm-2 col-form-label">Подтвердите пароль</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="new_confirm_password" name="new_confirm_password" placeholder="подтвердите пароль">
                            @error('new_confirm_password')
                            <small class="text-danger">
                                {{$message}}
                            </small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-outline-success float-right">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
