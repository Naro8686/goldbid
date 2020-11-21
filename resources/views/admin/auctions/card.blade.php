<div class="container">
    <div class="row d-flex justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped nowrap" width="100%" cellspacing="0" align="center"
                           id="auction_card">
                        <thead>
                        <tr>
                            <th>ID пользователя</th>
                            <th>Использовано Ставок</th>
                            <th>Использовано Бонусов</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $user)
                            <tr>
                                <td>{{$user['user_id']}}</td>
                                <td>{{$user['bet']}}</td>
                                <td>{{$user['bonus']}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
