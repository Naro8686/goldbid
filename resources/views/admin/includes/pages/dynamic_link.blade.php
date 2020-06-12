<div id="dynamic_link" class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">Подвал сайта</h1>
    <div id="no_social" class="card shadow mb-4 ">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Редактирование текстовых документов</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Ссылка</th>
                        <th>Расположение</th>
                        <th>Порядок</th>
                        <th>Вкл/Выкл</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th colspan="6">
                            <button type="button" class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#exampleModal" data-type="insert" data-social="false">добавить</button>
                        </th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($footers['left'] as $left)
                        <tr>
                            <td>
                                <p class="name">{{$left->name}}</p>
                            </td>
                            <td>
                                <a href="{{url($left->link)}}" target="_blank" class="link">{{url($left->link)}}</a>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select name="float" class="form-control" data-id="{{$left->id}}">
                                        <option @if($left->float==='left') selected @endif value="left">Слева
                                        </option>
                                        <option @if($left->float==='right') selected @endif value="right">Справа
                                        </option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <select name="position" class="form-control" data-id="{{$left->id}}">
                                    @for($position=1;$position<=$loop->count;$position++)
                                        <option @if($left->position===$position) selected
                                                @endif value="{{$position}}">{{$position}}</option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-toggle @if($left->show) active @endif"
                                        data-toggle="button" @if($left->show) aria-pressed="true"
                                        @else aria-pressed="false" @endif  autocomplete="off"
                                        data-id="{{$left->id}}">
                                    <span class="handle"></span>
                                </button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-info"
                                            data-toggle="modal" data-target="#exampleModal"
                                            data-type="update" data-id="{{$left->id}}" data-social="false"
                                            data-meta-title="{{$left->page->title}}"
                                            data-meta-keywords="{{$left->page->keywords}}"
                                            data-meta-description="{{$left->page->description}}"
                                            data-meta-content="{{$left->page->content}}">изменить</button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-type="delete" data-id="{{$left->id}}">удалить</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($footers['right'] as $right)
                        <tr>
                            <td>
                                <p class="name">{{$right->name}}</p>
                            </td>
                            <td>
                                <a href="{{url($right->link)}}" target="_blank" class="link">{{url($right->link)}}</a>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select name="float" class="form-control" data-id="{{$right->id}}">
                                        <option @if($right->float==='left') selected @endif value="left">Слева
                                        </option>
                                        <option @if($right->float==='right') selected @endif value="right">Справа
                                        </option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <select name="position" class="form-control" data-id="{{$right->id}}">
                                    @for($position=1;$position<=$loop->count;$position++)
                                        <option @if($right->position===$position) selected
                                                @endif value="{{$position}}">{{$position}}</option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-toggle @if($right->show) active @endif"
                                        data-toggle="button" @if($right->show) aria-pressed="true"
                                        @else aria-pressed="false" @endif  autocomplete="off"
                                        data-id="{{$right->id}}">
                                    <span class="handle"></span>
                                </button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-info"
                                            data-toggle="modal" data-target="#exampleModal"
                                            data-type="update" data-id="{{$right->id}}" data-social="false"
                                            data-meta-title="{{$right->page->title}}"
                                            data-meta-keywords="{{$right->page->keywords}}"
                                            data-meta-description="{{$right->page->description}}"
                                            data-meta-content="{{$right->page->content}}">изменить</button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-type="delete" data-id="{{$right->id}}">удалить</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="social" class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Редактирование соц сетей</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped dataTable" width="100%" cellspacing="0" align="center">
                    <thead>
                    <tr>
                        <th>Иконка</th>
                        <th>Наименование</th>
                        <th>Ссылка</th>
                        <th>Порядок</th>
                        <th>Вкл/Выкл</th>
                        <th>Действие</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th colspan="6">
                            <button type="button" class="btn btn-sm btn-success float-right" data-toggle="modal" data-target="#exampleModal" data-type="insert" data-social="true">добавить</button>
                        </th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($footers['social'] as $social)
                        <tr>
                            <td><img class="img-fluid img-thumbnail" src="{{asset($social->icon)}}" alt=""
                                     width="50"></td>
                            <td>
                                <p class="name">{{$social->name}}</p>
                            </td>
                            <td>
                                <p>
                                    <a href="{{$social->link}}" target="_blank" class="link">{{$social->link}}</a>
                                </p>
                            </td>
                            <td>
                                <select name="position" class="form-control" data-id="{{$social->id}}">
                                    @for($position=1;$position<=$loop->count;$position++)
                                        <option @if($social->position===$position) selected
                                                @endif value="{{$position}}">{{$position}}</option>
                                    @endfor
                                </select>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-toggle @if($social->show) active @endif"
                                        data-toggle="button" @if($social->show) aria-pressed="true"
                                        @else aria-pressed="false" @endif  autocomplete="off"
                                        data-id="{{$social->id}}">
                                    <span class="handle"></span>
                                </button>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" data-type="update" data-id="{{$social->id}}" data-social="true">изменить</button>
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal" data-type="delete" data-id="{{$social->id}}">удалить</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
