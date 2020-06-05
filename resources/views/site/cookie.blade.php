@extends('layouts.site')
@section('title') Файлы cookie  @endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/style.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container urinfo"><br>
            <P ALIGN=CENTER STYLE="margin-bottom: 0.11in; background: #ffffff; line-height: 100%"><A NAME="_GoBack"></A>
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3><B>УСЛОВИЯ
                                ИСПОЛЬЗОВАНИЯ ФАЙЛОВ COOKIE</B></FONT></FONT></FONT></P>
            <P ALIGN=CENTER STYLE="margin-bottom: 0.11in; background: #ffffff; line-height: 100%">
                <BR><BR>
            </P>
            <P STYLE="text-indent: 0.49in; margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3>На
                            сайте </FONT></FONT></FONT><A HREF="{{route('site.home')}}"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><SPAN LANG="en-US"></SPAN></FONT></FONT><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3></FONT></FONT><FONT FACE="Times New Roman, serif"><FONT
                            SIZE=3><SPAN LANG="en-US">{{route('site.home')}}</SPAN></FONT></FONT><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3>.</FONT></FONT><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><SPAN LANG="en-US">ru</SPAN></FONT></FONT></A><FONT
                    COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3>&nbsp;и
                            его поддоменах (далее —&nbsp;</FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><B>Сайт</B></FONT></FONT></FONT><FONT
                    COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3>)
                            используются файлы cookie.</FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3>Файлы
                            cookie — это&nbsp;</FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><SPAN STYLE="background: #ffffff">небольшие
текстовые файлы, которые после просмотра
Пользователем фрагментов Сайта
сохраняются на его устройстве.</SPAN></FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3><SPAN
                                STYLE="background: #ffffff">Использование
файлов cookie позволяет контролировать
доступность Сайта, анализировать данные,
а также понимать, как развивать оказываемые
услуги.</SPAN></FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3><SPAN
                                STYLE="background: #ffffff">На
Сайте используются следующие типы
файлов cookie:</SPAN></FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236">&nbsp; &nbsp; &nbsp; <FONT FACE="Times New Roman, serif"><FONT
                            SIZE=3>1.&nbsp;</FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><B>Технические
                                файлы cookie:</B></FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3>&nbsp;они
                            необходимы для корректной работы Сайта
                            и вспомогательных сервисов. Такие файлы
                            cookie позволяют определять аппаратное и
                            программное обеспечение устройства
                            Пользователя; выявлять ошибки при работе
                            Сайта; тестировать новые функции для
                            повышения производительности Сайта.</FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236">&nbsp; &nbsp; &nbsp;</FONT><FONT COLOR="#2f3236">&nbsp;<FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3>2.</FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><B>&nbsp;Файлы
                                cookie для аутентификации</B></FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3>:
                            они необходимы, чтобы запоминать
                            Пользователей. Благодаря таким файлам
                            Пользователю при новом посещении Сайта
                            не нужно заново вводить авторизационные
                            данные.</FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236">&nbsp; &nbsp; &nbsp; <FONT FACE="Times New Roman, serif"><FONT
                            SIZE=3>3.</FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><B>&nbsp;Аналитические
                                файлы cookie</B></FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3>:
                            они позволяют подсчитывать количество
                            Пользователей Сайта; определять, какие
                            действия Пользователь совершает на
                            Сайте (посещаемые страницы, время и
                            количество просмотренных страниц). Сбор
                            аналитических данных осуществляется
                            через партнеров, в том числе Google Analytics,
                            Yandex Metrika.</FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236">&nbsp; &nbsp; &nbsp; <FONT FACE="Times New Roman, serif"><FONT
                            SIZE=3>4.</FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3><B>&nbsp;Рекламные
                                файлы cookie</B></FONT></FONT></FONT><FONT COLOR="#2f3236"><FONT
                        FACE="Times New Roman, serif"><FONT SIZE=3>:
                            они помогают анализировать, из каких
                            источников Пользователь перешел на
                            Сайт, а также персонализировать рекламные
                            сообщения.</FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3>Срок
                            хранения файлов cookie зависит от конкретного
                            типа, но в любом случае не превышает
                            срока, необходимого для достижения
                            целей обработки персональных данных.</FONT></FONT></FONT></P>
            <P STYLE="text-indent: 0.49in; margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3>При
                            посещении Сайта Оператор запрашивает
                            согласие Пользователя на использование
                            файлов cookie. Для прекращения обработки
                            файлов cookie Пользователь может изменить
                            настройки используемых браузеров на
                            всех устройствах (компьютер, мобильные
                            устройства).&nbsp;</FONT></FONT></FONT></P>
            <P STYLE="margin-bottom: 0in; background: #ffffff; line-height: 100%">
                <FONT COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3><B>ВАЖНО:&nbsp;</B></FONT></FONT></FONT><FONT
                    COLOR="#2f3236"><FONT FACE="Times New Roman, serif"><FONT SIZE=3>при
                            отказе от использования файлов cookie
                            отдельные функции Сайта могут быть
                            недоступными, что повлияет на возможность
                            использования Сайта.</FONT></FONT></FONT></P>
        </div>
    </div>
@endsection
