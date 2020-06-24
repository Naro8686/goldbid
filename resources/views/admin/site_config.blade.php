@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Настройки сайта</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Параметры</h6>
            </div>
            <div class="card-body">
                <form action="{{route('admin.settings.site')}}" method="POST">
                    <div class="form-row">
                        @csrf
                        <div class="form-group col-md-6">
                            <label for="email" class="col-form-label">E-mail</label>
                            <input type="email" class="form-control" id="email"
                                name="email" value="{{old('email')??$site->email}}">
                            @error('email')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone" class="col-form-label">Телефон</label>
                            <input
                                type="text" class="form-control mask" id="phone" name="phone_number"
                                value="{{old('phone_number')??$site->phone_number}}">
                            @error('phone_number')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="inputState">Срок хранения завершённых аукционов </label>
                            <select id="inputState" class="form-control" name="storage_period_month">
                                <option value="">всегда</option>
                                <option @if((int)$site->storage_period_month === 3) selected @endif value="3">3 мес.</option>
                                <option @if((int)$site->storage_period_month === 6) selected @endif value="6">6 мес.</option>
                                <option @if((int)$site->storage_period_month === 9) selected @endif value="9">9 мес.</option>
                                <option @if((int)$site->storage_period_month === 12) selected @endif value="12">12 мес.</option>
                            </select>
                            @error('storage_period_auction')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-md-3 d-flex flex-column justify-content-center align-items-center">
                            <label for="inputState">Включение сайта для пользователей</label>
                            <div class="d-flex align-items-center h-100">
                                <button type="button"
                                        class="btn btn-sm btn-toggle @if($site->site_enabled) active @endif"
                                        data-toggle="button" @if($site->site_enabled) aria-pressed="true"
                                        @else aria-pressed="false" @endif  autocomplete="off"
                                        onclick='oNoFF("{{route('admin.settings.site')}}",{site_enabled:($(this).attr("aria-pressed") === "true" ? 0 : 1),},"POST")'>
                                    <span class="handle"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-block btn-outline-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
