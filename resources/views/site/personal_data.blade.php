@extends('layouts.site')
@section('title') Персональные данные  @endsection
@section('name-page') Персональные данные @endsection
@push('css')
    <link rel="stylesheet" href="{{asset('site/css/style.css')}}">
@endpush
@section('content')
    <div class="main">
        <div class="container urinfo"><br>
            <p align="CENTER" style="margin-top: 0.17in; margin-bottom: 0.06in; line-height: 100%">
                <font face="Times New Roman, serif"><font size="3" style="font-size: 11pt"><b>Согласие
                            на обработку персональных данных</b></font></font></p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">	Отмечая
                        данное согласие, "Пользователь"
                        сайта </font></font>{{route('site.home')}}<font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">
                        добровольно дает согласие на обработку
                        "Администратором" (ИП Бутынцевым
                        К.В.) персональных данных "Пользователя".</font></font></p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">	Обработка
                        персональных данных в соответствии со
                        ст. 9 Федерального закона № 153-ФЗ "О
                        персональных данных" включает в себя
                        обработку, сбор, систематизацию,
                        накопление, хранение, уточнение,
                        обновление, изменение, использование,
                        передачу третьим лицам, обезличивание,
                        блокирование и уничтожение следующих
                        персональных данных "Пользователя":
                        фамилия, имя, отчество, гражданство,
                        дата рождения, пол, адрес регистрации
                        по месту жительства, номер контактного
                        телефона, ИНН, адрес электронной почты,
                        реквизиты документа удостоверяющего
                        личность, реквизиты счета в платежной
                        системе (кредитной организации), размер
                        дохода полученного от Администратора.</font></font></p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">	Персональные
                        данные предоставлены "Пользователем"
                        путем отправки их на электронную почту
                    </font></font><a href="mailto:goldbid24@gmail.com"><font size="3" style="font-size: 11pt">OnlineBigGame@yandex.ru</font></a><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">,
                        или внесения их при регистрации на сайте
                    </font></font><a href="{{route('site.home')}}"><font size="3" style="font-size: 11pt">{{route('site.home')}}</font></a><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">
                        добровольно и являются достоверными. </font></font>
            </p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">	"Пользователь"
                        извещен о том, что в случае недостоверности
                        предоставленных персональных данных
                        "Администратор" оставляет за собой
                        право прекратить обслуживание посредством
                        сайта </font></font>{{route('site.home')}}<font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">.
                    </font></font>
            </p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">	"Администратор"
                        обязуется обрабатывать персональные
                        данные "Пользователя" в&nbsp;порядке,
                        установленном действующим законодательством
                        РФ и </font></font><a href="https://images.wbstatic.net/oferta/political-wildberries.pdf?_ga=2.226121946.1151055178.1556800770-923657617.1548768720"><font color="#00000a"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt"><span style="text-decoration: none">«Политикой
конфиденциальности»</span></font></font></font></a><font color="#ff0000"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">
                        </font></font></font><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">в&nbsp;отношении
                        организации обработки и&nbsp;обеспечения
                        безопасности персональных данных.</font></font></p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">	"Пользователь"
                        согласен, что его персональные данные
                        будут обрабатываться способами,
                        соответствующими целям обработки
                        персональных данных, без возможности
                        принятия решения на основании исключительно
                        автоматизированной обработки его
                        персональных данных.</font></font></p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">	Персональные
                        данные предоставлены "Пользователем"
                        с целью предоставления ему Товаров,
                        включая, но не ограничиваясь: идентификацией
                        участника в программе лояльности,
                        обеспечения процедуры начисления, учета
                        и расходования бонусов, осуществление
                        доставки, предоставление сервисных
                        услуг, распространения информационных
                        и рекламных сообщений (по SMS, электронной
                        почте, телефону, иным средствам связи),
                        получения обратной связи.</font></font></p>
            <p align="JUSTIFY" style="margin-bottom: 0.14in; line-height: 100%"><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt"><span style="background: #ffffff">	"Пользователь"
согласен с тем, что его персональные
данные могут быть использованы
"Администратором" в&nbsp;целях
направления "Пользователю" рекламы
и&nbsp;информации от&nbsp;"Администратора"
и/или его партнеров по&nbsp;сетям
электросвязи, в&nbsp;том числе через
Интернет, в&nbsp;соответствии со&nbsp;ст.18
Федерального закона " </span></font></font><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt">№
                        38-ФЗ </font></font><font face="Times New Roman, serif"><font size="3" style="font-size: 11pt"><span style="background: #ffffff">«О&nbsp;рекламе».</span></font></font></p>
            <p align="JUSTIFY" style="margin-top: 0.19in; margin-bottom: 0.19in; background: #ffffff; line-height: 100%">
                <font face="Times New Roman, serif"><font size="3"><font color="#ff0000"><font size="3" style="font-size: 11pt">	</font></font><font size="3" style="font-size: 11pt">Настоящее
                            согласие может быть отозвано "Пользователем"
                            в любой момент путем направления
                            письменного требования в адрес
                            Администратора по электронному
                            адресу:&nbsp;<a href="mailto:goldbid24@gmail.com">OnlineBigGame@yandex.ru</a></font></font></font></p>
            <p align="JUSTIFY" style="margin-top: 0.19in; margin-bottom: 0.19in; background: #ffffff; line-height: 100%">
                <font face="Times New Roman, serif"><font size="3"><font size="3" style="font-size: 11pt">	До
                            сведения "Пользователя" доведено,
                            что в случае отзыва "Пользователем"
                            согласия на&nbsp;обработку его персональных
                            данных Администратор обязуется прекратить
                            их&nbsp;обработку или обеспечить прекращение
                            такой обработки (если обработка
                            персональных данных осуществляется
                            другим лицом, действующим по&nbsp;поручению
                            Администратора) и&nbsp;в&nbsp;случае, если
                            сохранение персональных данных более
                            не&nbsp;требуется для целей обработки
                            персональных данных, уничтожить
                            персональные данные или обеспечить
                            их&nbsp;уничтожение (если обработка
                            персональных данных осуществляется
                            другим лицом, действующим по&nbsp;поручению
                            Администратора) в&nbsp;срок, не&nbsp;превышающий
                            тридцати дней с&nbsp;даты поступления
                            указанного отзыва, если иное не&nbsp;предусмотрено
                            договором, стороной которого,
                            выгодоприобретателем или поручителем
                            по&nbsp;которому является "Пользователь".</font></font></font></p>
            <p align="JUSTIFY" style="margin-top: 0.19in; margin-bottom: 0.19in; background: #ffffff; line-height: 100%">
                <font face="Times New Roman, serif"><font size="3"><font size="3" style="font-size: 11pt">	Данное
                            согласие действует с момента регистрации
                            на сайте </font><a href="{{route('site.home')}}"><font size="3" style="font-size: 11pt"><span lang="en-US">{{route('site.home')}}</span></font><font size="3" style="font-size: 11pt">.</font><font size="3" style="font-size: 11pt"><span lang="en-US">ru</span></font></a><font size="3" style="font-size: 11pt">
                            и действует бессрочно. </font></font></font>
            </p>
            <p align="JUSTIFY" style="margin-top: 0.19in; margin-bottom: 0.19in; background: #ffffff; line-height: 100%">
                <br><br>
            </p>
            <p style="margin-bottom: 0.14in; line-height: 100%"><br><br>
            </p>
        </div>
    </div>
@endsection
