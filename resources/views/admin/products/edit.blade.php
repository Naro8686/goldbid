@extends('layouts.admin')
@section('content')
    <div class="container-fluid">
        @include('admin.products.groups')
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Изменение шаблона </h6>
            </div>
            <div class="card-body">
                <form action="{{route('admin.products.update',$product->id)}}" method="POST"
                      enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <div class="form-group row">
                        <label for="title" class="col-sm-2 col-form-label">Наименование </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                   name="title" value="{{old('title')??$product->title}}">
                            @error('title') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="short_desc" class="col-sm-2 col-form-label">Пояснение</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('short_desc') is-invalid @enderror"
                                   id="short_desc" name="short_desc" value="{{old('short_desc')??$product->short_desc}}">
                            @error('short_desc') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company_id" class="col-sm-2 col-form-label">Производитель</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="company_id" id="company_id">
                                @foreach($companies as $company)
                                    <option @if((int)old('company_id')===(int)$company->id || (int)$product->company->id===(int)$company->id) selected
                                            @endif value="{{$company->id}}">{{$company->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="category_id" class="col-sm-2 col-form-label">Группа товара</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="category_id" id="category_id">
                                @foreach($categories as $category)
                                    <option @if((int)old('category_id')===(int)$category->id || (int)$product->category->id===(int)$category->id) selected
                                            @endif value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="start_price" class="col-sm-2 col-form-label">Стартовая цена (руб)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('start_price') is-invalid @enderror"
                                   id="start_price"
                                   name="start_price" value="{{old('start_price')??$product->start_price}}">
                            @error('start_price') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="full_price" class="col-sm-2 col-form-label">Полная цена (руб)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('full_price') is-invalid @enderror"
                                   id="full_price"
                                   name="full_price" value="{{old('full_price')??$product->full_price}}">
                            @error('full_price') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="bot_shutdown_price" class="col-sm-2 col-form-label">Отключение бота (руб)</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('bot_shutdown_price') is-invalid @enderror"
                                   id="bot_shutdown_price"
                                   name="bot_shutdown_price" value="{{old('bot_shutdown_price')??$product->bot_shutdown_price}}">
                            @error('bot_shutdown_price') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="step_time" class="col-sm-2 col-form-label">Таймер (сек)</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   class="form-control @error('step_time') is-invalid @enderror"
                                   id="step_time"
                                   name="step_time" min="1" value="{{old('step_time')??$product->step_time}}">
                            @error('step_time') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="step_price" class="col-sm-2 col-form-label">Шаг ставки (коп)</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   class="form-control @error('step_price') is-invalid @enderror"
                                   id="step_price"
                                   name="step_price" min="1" value="{{old('step_price')??$product->step_price}}">
                            @error('step_price') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="to_start" class="col-sm-2 col-form-label">Автозапуск (мин)</label>
                        <div class="col-sm-10">
                            <input type="number"
                                   class="form-control @error('to_start') is-invalid @enderror"
                                   id="to_start"
                                   name="to_start" min="1" value="{{old('to_start')??$product->to_start}}">
                            @error('to_start') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="exchange" class="col-sm-2 col-form-label">Обмен на ставки</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input type="checkbox" @if(old('exchange')==='on' || (bool)$product->exchange) checked
                                       @endif class="form-check-input" name="exchange" id="exchange">
                            </div>
                            @error('exchange') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="buy_now" class="col-sm-2 col-form-label">Купить сейчас</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input type="checkbox" @if(old('buy_now')==='on' || (bool)$product->buy_now) checked
                                       @endif class="form-check-input" name="buy_now" id="buy_now">
                            </div>
                            @error('buy_now') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="visibly" class="col-sm-2 col-form-label">Выводит на сайт</label>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input type="checkbox" @if(old('visibly')==='on' || (bool)$product->visibly) checked
                                       @endif class="form-check-input" name="visibly" id="visibly">
                            </div>
                            @error('visibly') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Изображения</label>
                        <div class="col-sm-2 m-2">
                            <input type="text"
                                   placeholder="alt 1"
                                   name="alt_1"
                                   class="form-control @error('alt_1') is-invalid @enderror"
                                   value="{{old('alt_1')??$product->alt_1}}">
                            <div class="image-upload">
                                <div class="image-edit">
                                    <input type='file' class="imageUpload" id="imageUpload1"
                                           name="file_1"
                                           accept=".png, .jpg, .jpeg, .gif"/>
                                    <label for="imageUpload1"></label>
                                </div>
                                <div class="image-preview">
                                    <div class="imagePreview" @if($product->img_1) style="background-image: url('{{asset($product->img_1)}}')" @endif></div>
                                </div>
                            </div>
                            @error('file_1') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-sm-2 m-2">
                            <input type="text"
                                   placeholder="alt 2"
                                   name="alt_2"
                                   class="form-control @error('alt_2') is-invalid @enderror"
                                   value="{{old('alt_2')??$product->alt_2}}">
                            <div class="image-upload">
                                <div class="image-edit">
                                    <input type='file' class="imageUpload" id="imageUpload2"
                                           name="file_2"
                                           accept=".png, .jpg, .jpeg, .gif"/>
                                    <label for="imageUpload2"></label>
                                </div>
                                <div class="image-preview">
                                    <div class="imagePreview" @if($product->img_2) style="background-image: url('{{asset($product->img_2)}}')" @endif></div>
                                </div>
                            </div>
                            @error('file_2') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-sm-2 m-2">
                            <input type="text"
                                   placeholder="alt 3"
                                   name="alt_3"
                                   class="form-control @error('alt_3') is-invalid @enderror"
                                   value="{{old('alt_3')??$product->alt_3}}">
                            <div class="image-upload">
                                <div class="image-edit">
                                    <input type='file' class="imageUpload"
                                           id="imageUpload3"
                                           name="file_3"
                                           accept=".png, .jpg, .jpeg, .gif"/>
                                    <label for="imageUpload3"></label>
                                </div>
                                <div class="image-preview">
                                    <div class="imagePreview" @if($product->img_3) style="background-image: url('{{asset($product->img_3)}}')" @endif></div>
                                </div>
                            </div>
                            @error('file_3') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                        <div class="col-sm-2 m-2">
                            <input type="text"
                                   placeholder="alt 4"
                                   name="alt_4"
                                   class="form-control @error('alt_4') is-invalid @enderror"
                                   value="{{old('alt_4')??$product->alt_4}}">
                            <div class="image-upload">
                                <div class="image-edit">
                                    <input type='file' class="imageUpload"
                                           id="imageUpload4"
                                           name="file_4"
                                           accept=".png, .jpg, .jpeg, .gif"/>
                                    <label for="imageUpload4"></label>
                                </div>
                                <div class="image-preview">
                                    <div class="imagePreview" @if($product->img_4) style="background-image: url('{{asset($product->img_4)}}')" @endif></div>
                                </div>
                            </div>
                            @error('file_4') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="desc" class="col-sm-2 col-form-label">Описание товара</label>
                        <div class="col-sm-10">
                            <textarea class="form-control ck__textarea" id="desc" name="desc"
                                      rows="10">{{old('desc')??$product->desc}}</textarea>
                            @error('desc') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="specify" class="col-sm-2 col-form-label">Характеристика</label>
                        <div class="col-sm-10">
                            <textarea class="form-control ck__textarea" id="specify" name="specify"
                                      rows="10">{{old('specify')??$product->specify}}</textarea>
                            @error('specify') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="terms" class="col-sm-2 col-form-label">Условия аукциона</label>
                        <div class="col-sm-10">
                            <textarea class="form-control ck__textarea" id="terms" name="terms"
                                      rows="10">{{old('terms')??$product->terms}}</textarea>
                            @error('terms') <small class="text-danger">{{$message}}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                            <input type="submit" class="btn btn-outline-success float-right">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
