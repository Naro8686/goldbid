@extends('layouts.admin')
@section('content')

    <div class="container-fluid">
        @if(isset($meta))
            @include('admin.includes.pages.seo_update')
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Оформление заказа</h6>
            </div>
            <div class="card-body">
                <div id="accordion">
                    @foreach($steps as $step => $order)
                        <div class="card">
                            <div class="card-header" id="heading{{$step}}">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$step}}"
                                            aria-expanded="false" aria-controls="collapse{{$step}}">
                                        Шаг {{$step}}
                                    </button>
                                </h5>
                            </div>
                            <div id="collapse{{$step}}" class="collapse" aria-labelledby="heading{{$step}}"
                                 data-parent="#accordion">
                                <div class="card-body">
                                    @if($step === 1)
                                        @foreach($order as $page)
                                            @if($page->for_winner)
                                                <a href="{{route('admin.orders.edit',$page->id)}}" type="button"
                                                   class="btn btn-primary btn-lg btn-block">Для победителя</a>
                                            @else
                                                <a href="{{route('admin.orders.edit',$page->id)}}" type="button"
                                                   class="btn btn-primary btn-lg btn-block">Для остальных</a>
                                            @endif
                                        @endforeach
                                    @elseif($step === 2)
                                        @foreach($order as $page)
                                            @if($page->type === \App\Models\Auction\Step::PRODUCT)
                                                <a href="{{route('admin.orders.edit',$page->id)}}" type="button"
                                                   class="btn btn-primary btn-lg btn-block">Товар</a>
                                            @elseif($page->type === \App\Models\Auction\Step::MONEY)
                                                <a href="{{route('admin.orders.edit',$page->id)}}" type="button"
                                                   class="btn btn-primary btn-lg btn-block">Деньги</a>
                                            @elseif($page->type === \App\Models\Auction\Step::BET)
                                                <a href="{{route('admin.orders.edit',$page->id)}}" type="button"
                                                   class="btn btn-primary btn-lg btn-block">Ставки</a>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <br>
                    @endforeach
                </div>
                {{--                <div class="table-responsive">--}}
                {{--                    <table class="table table-striped" width="100%" cellspacing="0" align="center">--}}
                {{--                        <tbody>--}}
                {{--                        @foreach($steps as $step)--}}
                {{--                            <tr>--}}
                {{--                                <td>--}}
                {{--                                    <p class="name">{{$step->step}}</p>--}}
                {{--                                </td>--}}
                {{--                                <td>--}}
                {{--                                    <p class="name">{{$step->type}}</p>--}}
                {{--                                </td>--}}
                {{--                                <td>--}}
                {{--                                    <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">--}}
                {{--                                        <a href="{{route('admin.orders.edit',[$step->id])}}"--}}
                {{--                                           class="btn btn-info">изменить</a>--}}
                {{--                                    </div>--}}
                {{--                                </td>--}}
                {{--                            </tr>--}}
                {{--                        @endforeach--}}
                {{--                        </tbody>--}}
                {{--                    </table>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>

@endsection
