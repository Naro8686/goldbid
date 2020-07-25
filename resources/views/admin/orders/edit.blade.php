@extends('layouts.admin')
@section('content')
    <div class="container-fluid">

        <form action="{{route('admin.orders.update',[$step->id])}}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('admin.pages.order')}}" class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                        <span class="text">назад</span>
                    </a>
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{$info}}</h6>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="text">текст</label>
                        <textarea name="text" rows="7"
                                  class="form-control @error('text') is-invalid @enderror"
                                  id="text">{{$step->text}}</textarea>
                        @error('text')
                        <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-center align-items-center pt-4 mt-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><h5>Теги которые можно использовать!</h5></li>
                            <li class="list-group-item">
                                <ul>
                                    @if($step->step===1)
                                        <li class="font-weight-bold">#title#</li>
                                    @else
                                        @if($step->type===\App\Models\Auction\Step::PRODUCT)
                                        @elseif($step->type===\App\Models\Auction\Step::MONEY)
                                            <li class="font-weight-bold">#money#</li>
                                        @elseif($step->type===\App\Models\Auction\Step::BET)
                                            <li class="font-weight-bold">#bet#</li>
                                            <li class="font-weight-bold">#bonus#</li>
                                        @endif
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <button class="btn btn-block btn-outline-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>
@endsection
