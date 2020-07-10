<div class="card shadow mb-4">
    <div class="card-body">
        <form class="container" method="POST" action="{{route('admin.products.add_group')}}">
            @csrf
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="company_name">Производитель</label>
                    <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name"
                           name="company_name" value="{{old('company_name')}}">
                    @error('company_name') <small class="text-danger">{{$message}}</small> @enderror
                </div>
                <div class="col-md-6">
                    <label for="category_name">Группа товара</label>
                    <input type="text" class="form-control @error('category_name') is-invalid @enderror" id="category_name"
                           name="category_name" value="{{old('category_name')}}">
                    @error('category_name') <small class="text-danger">{{$message}}</small> @enderror
                </div>
                <div class="col-md-12 mt-2">
                    <button type="submit" class="btn btn-block btn-outline-success">Добавить</button>
                </div>
            </div>
        </form>
    </div>
</div>
