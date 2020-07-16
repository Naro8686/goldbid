@extends('layouts.site')
@section('name-page')Аукцион@endsection
@push('css')
    <link href="{{asset('site/css/slick.css')}}" rel="stylesheet">
    <link href="{{asset('site/css/slick-theme.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('site/css/auction.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container">
            <div class="card">
                <div class="left" style="overflow: hidden;">
                    <div class="auction__slider">
                        @foreach($auction['images'] as $image)
                            <div>
                                <img src="{{asset($image['img'])}}" class="slide-img" alt="{{$image['alt']}}">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="dashboard">
                    @include('site.include.info')
                </div>
            </div>


            <div class="items" style="margin:40px 0">
                <div class="item active" id="description">Описаниие</div>
                <div class="item" id="specifications">Характеристики</div>
                <div class="item" id="services">Условия аукциона</div>
                <div class="item-text">
                    <div id="adescription">{!! $auction['desc'] !!}</div>
                    <div id="aspecifications">{!! $auction['specify'] !!}</div>
                    <div id="aservices">{!! $auction['terms'] !!}</div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script type="text/javascript">

            $(document).ready(function () {

                $("h2.descr").on('click', function () {
                    $("div.descr").toggle('none');
                });
                $("h2.har").on('click', function () {
                    $("div.har").toggle('none');
                });
                $("h2.usl").on('click', function () {
                    $("div.usl").toggle('none');
                });


                $(".dashboard").on('click', '.win', function () {
                    var swap = "";
                    var popap = $("#popap");
                    var overlay = $("#overlay");
                    var close = $(".alert .close");

                    if (swap == 1) {
                        popap.addClass("active");
                        overlay.addClass("active");

                        overlay.on('click', function () {
                            overlay.removeClass("active");
                            popap.removeClass("active");
                            $("#change-tovar").removeClass("active");
                        });

                        close.on('click', function () {
                            overlay.removeClass("active");
                            popap.removeClass("active");
                        });

                        return false;
                    } else {

                    }

                });

                $(".change .button").on('click', function () {
                    var popap = $("#popap");
                    var change = $('#change-tovar').html();
                    popap.html(change);
                });

                $("#popap").on('click', '#changeClick', function () {
                    $("#change-tovar").addClass("active");
                });

                $("input[name=no]").on("click", function () {
                    $("#change-tovar").removeClass("active");
                });

                // $("#changeYes").on('click', function () {
                //     var countBid = $(this).attr("data-countBid");
                //     var user_id = "";
                //     var tovar_id = "";
                //
                //     var popap = $("#popap");
                //     var overlay = $("#overlay");
                //
                //     overlay.removeClass("active");
                //     popap.removeClass("active");
                //     $("#change-tovar").removeClass("active");
                //
                //     $.post("php/change.php", {countBid, user_id, tovar_id}, function (data) {
                //     });
                //     popap.html(('<p class="alert">Вы уже выкупили товар.<input class="close" type="submit" value="OK"></p>'));
                //
                // });

            });
            // var tovarBuy = "";
            // var username = "";
            // var id = "";
            // var autobid = document.getElementById('autobid');
            // var actionAdd = true;
            // var actionRemove = true;
            //
            // if (tovarBuy == true) {
            //     console.log(1);
            // }

            // function get_auction() {
            //
            //     $.ajax({
            //         url: "auction_load.php?id=" + id + "&username=" + username,
            //         type: "get",
            //         success: function (result) {
            //             $('.dashboard').html(result);
            //         }
            //     })
            //     $.getJSON("php/balance.php").done(function (result) {
            //         $('.phpbalance').html(result[0]);
            //         $('.phpbonus').html(result[1]);
            //     });
            //     $.getJSON("php/status.php?id=" + id).done(function (result) {
            //         if (result == 1 && actionAdd == true) {
            //             addAutobid();
            //             action = false;
            //         }
            //         if (result == 2 && actionRemove == true) {
            //             removeAutobid();
            //             actionRemove = false;
            //         }
            //     });
            //
            //
            // }

            function addAutobid() {
                autobid.style = "display:flex";
            }

            function removeAutobid() {
                autobid.style = "display:none";
            }

            $("#description").click(function () {
                $("#adescription").fadeIn();
                $("#aspecifications, #aservices").hide();
                $("#specifications, #services").removeClass("active");
                $("#description").addClass("active");
            });
            $("#specifications").click(function () {
                $("#aspecifications").fadeIn();
                $("#adescription, #aservices").hide();
                $("#description, #services").removeClass("active");
                $("#specifications").addClass("active");
            });
            $("#services").click(function () {
                $("#aservices").fadeIn();
                $("#aspecifications, #adescription").hide();
                $("#specifications, #description").removeClass("active");
                $("#services").addClass("active");
            });

        </script>
    @endpush
@endsection
