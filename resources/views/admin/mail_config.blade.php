@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Настройки Е-майл</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Параметры</h6>
            </div>
            <div class="card-body">
                <form action="{{route('admin.settings.mail')}}" method="POST">
                    @csrf
                    <div class="form-group"><label for="driver" class="col-form-label">Driver</label>
                        <input
                            type="text" class="form-control" id="driver" name="driver"
                            value="{{$mail->driver??old('driver')}}">
                        @error('driver')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group"><label for="host" class="col-form-label">Host</label><input
                            type="text" class="form-control" id="host"
                            name="host" value="{{$mail->host??old('host')}}">
                        @error('host')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="port" class="col-form-label">Port</label>
                        <input type="text"
                               class="form-control"
                               id="port"
                               name="port"
                               value="{{$mail->port??old('port')}}">
                        @error('port')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="address" class="col-form-label">Address</label>
                        <input type="email"
                               class="form-control"
                               id="address"
                               name="from_address"
                               value="{{$mail->from_address??old('from_address')}}">
                        @error('address')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-form-label">Name</label>
                        <input type="text"
                               class="form-control"
                               id="name"
                               name="from_name"
                               value="{{$mail->from_name??old('from_name')}}">
                        @error('name')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="encryption" class="col-form-label">Encryption</label>
                        <input type="text"
                               class="form-control"
                               id="encryption"
                               name="encryption"
                               value="{{$mail->encryption??old('encryption')}}">
                        @error('encryption')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="username" class="col-form-label">Username</label>
                        <input type="text"
                               class="form-control"
                               id="username"
                               name="username"
                               value="{{$mail->username??old('username')}}">
                        @error('username')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="col-form-label">Password</label>
                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               value="{{$mail->getPassword()??old('password')}}">
                        @error('password')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-block btn-outline-success">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
