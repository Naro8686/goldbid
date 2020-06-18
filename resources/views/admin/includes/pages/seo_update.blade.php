<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Meta Tag</h6>
    </div>
    <div class="card-body">
        <form action="{{route('admin.pages.seo.update',[$meta->id])}}" method="POST">
            @csrf

            <div class="form-group"><label for="social-meta-title" class="col-form-label">Seo Title</label><input
                    type="text" class="form-control" id="social-meta-title" name="title"
                    value="{{$meta->title}}"></div>
            <div class="form-group"><label for="social-meta-keywords" class="col-form-label">Seo
                    Keywords</label><input type="text" class="form-control" id="social-meta-keywords"
                                           name="keywords" value="{{$meta->keywords}}"></div>
            <div class="form-group"><label for="social-meta-description" class="col-form-label">Seo
                    Description</label><input type="text" class="form-control" id="social-meta-description"
                                              name="description" value="{{$meta->description}}"></div>
            <div class="form-group">
                <input type="submit" class="btn btn-block btn-outline-success" id="social-meta-submit">
            </div>
        </form>
    </div>
</div>
