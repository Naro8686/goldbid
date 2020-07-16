@extends('layouts.admin')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('admin.dashboard')}}"
                   class="btn btn-light btn-icon-split float-right mb-2">
                            <span class="icon text-gray-600">
                              <i class="fas fa-arrow-left"></i>
                            </span>
                    <span class="text">назад</span>
                </a>
            </div>
        </div>
        <h1 class="h3 mb-2 text-gray-800">Аукционы SEO</h1>
        @include('admin.includes.pages.seo_update')
    </div>
@endsection
