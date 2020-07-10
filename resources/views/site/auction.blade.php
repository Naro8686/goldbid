@extends('layouts.site')
@section('name-page')Пакеты ставок@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/auction.css')}}">
@endpush
@section('content')

    <div class="main">
{{--        <div class="container">--}}
{{--            <div class="card">--}}
{{--                <div class="left" style="overflow: hidden;">--}}
{{--                    <div class="slaider">--}}
{{--                        <? for ($i = 0; $i < count($image) - 1; $i++): ?>--}}
{{--                        <div><img src="<?=$image[$i]?>" class="slide-img"></div>--}}
{{--                        <? endfor; ?>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <?--}}
{{--                $query = $link->prepare("SELECT * FROM `tovary` WHERE `id` = :id");--}}
{{--                $params = ['id' => $_GET['id']];--}}
{{--                $query->execute($params);--}}
{{--                $tovary = $query->fetchAll();--}}
{{--                foreach ($tovary as $tovar) {--}}
{{--                    $tovar;--}}
{{--                }--}}
{{--                ?>--}}
{{--                <div id="autobid" class="autobid">--}}
{{--                    <p>Введите кол-во<br> ставок</p>--}}
{{--                    <form method="POST" action="auction.php?id=<?=$_GET['id']?>">--}}
{{--                        <input type="hidden" name="id_tovar" value="<?=$_GET['id']?>">--}}
{{--                        <input class="value" name="sum" type="text">--}}
{{--                        <input type="submit" class="btn" name="autobid" value="Автоставка">--}}
{{--                    </form>--}}
{{--                </div>--}}

{{--                <div class="dashboard">--}}
{{--                    <!-- сюда грузиться вся инфа по ajax -->--}}
{{--                </div>--}}
{{--            </div>--}}


{{--            <div class="items" style="margin:40px 0">--}}
{{--                <div class="item active" id="description">Описаниие</div>--}}
{{--                <div class="item" id="specifications">Характеристики</div>--}}
{{--                <div class="item" id="services">Условия аукциона</div>--}}
{{--                <div class="item-text">--}}
{{--                    <div id="adescription"><?=$tovar['description']?></div>--}}
{{--                    <div id="aspecifications"><?=$tovar['specifications']?></div>--}}
{{--                    <div id="aservices"><?=$tovar['services']?></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection
