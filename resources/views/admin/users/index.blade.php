@extends('layouts.admin')
@section('content')
    <div class="modal fade" tabindex="1" id="cardModal" role="dialog" aria-labelledby="cardModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="cardModalLabel"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыт</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Пользователи</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <div class="row text-center">
                    <div class="col-md-3">
                        <span class="m-0 font-weight-bold text-primary">Всего пользователей в базе: <span
                                class="text-dark">{{$usersInfo['count']}}</span></span>

                    </div>
                    <div class="col-md-3">
                        <span class="m-0 font-weight-bold text-primary">Активных: <span
                                class="text-dark">{{$usersInfo['active']}}</span></span>

                    </div>
                    <div class="col-md-3">
                        <span class="m-0 font-weight-bold text-primary">Заблокированных: <span
                                id="count_banned"
                                class="text-dark">{{$usersInfo['banned']}}</span></span>

                    </div>
                    <div class="col-md-3">
                        <span class="m-0 font-weight-bold text-primary">Online: <span
                                class="text-dark">{{$usersInfo['online']}}</span></span>

                    </div>
                </div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped nowrap" width="100%" cellspacing="0" align="center"
                           id="users_table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дата регистрации</th>
                            <th>Ник</th>
                            <th>Фамилия</th>
                            <th>Дата рождения</th>
                            <th>Телефон</th>
                            <th>E-mail</th>
                            <th>Ставок</th>
                            <th>Бонусов</th>
                            <th>Участии</th>
                            <th>Побед</th>
                            <th>Рефералов</th>
                            <th>Бан</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            $(function () {
                $('#users_table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('admin.users.index') }}',
                    "language": {
                        "url": "{{url('/datatables/lang/Russian.json')}}"
                    },
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'nickname', name: 'nickname'},
                        {data: 'lname', name: 'lname'},
                        {data: 'birthday', name: 'birthday'},
                        {data: 'phone', name: 'phone'},
                        {data: 'email', name: 'email'},
                        {data: 'bet', name: 'bet'},
                        {data: 'bonus', name: 'bonus'},
                        {data: 'participation', name: 'participation'},
                        {data: 'win', name: 'win'},
                        {data: 'count_referral', name: 'count_referral'},
                        {data: 'has_ban', name: 'has_ban'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            });
            $(function () {
                $(document).on('click', '[data-target="#cardModal"]', function (e) {
                    let url = $(this).data('href');
                    let method = 'GET';
                    let data = null;
                    let modal = $($(this).data('target'));
                    modal.find('.modal-body').html('');
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: method,
                        url: url,
                        data: data,
                        success: (data) => {
                            if (data.success && data.html) {
                                modal.find('.modal-body').html(data.html);
                                modal.find('#cardModalLabel').text(data.title);
                            }
                        },
                        error: (error) => {
                            console.log(error)
                        }
                    });
                })
            })
        </script>
    @endpush
@endsection
