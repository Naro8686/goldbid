<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel ="apple-touch-icon" sizes ="180x180" href ="{{asset('apple-touch-icon.png')}}" >
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('site.webmanifest')}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - {{ config('app.name', 'GoldBid') }}</title>
    <link href="{{asset('administration/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="{{asset('administration/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('administration/css/button.css')}}" rel="stylesheet">
    <link href="{{asset('administration/css/preloader.css')}}" rel="stylesheet">
    <link href="{{asset('administration/css/custom.css')}}" rel="stylesheet">
    <link href="{{asset('datatables/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{asset('ckeditor/ckeditor.js')}}"></script>
    @stack('css')

</head>
<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('admin.dashboard')}}">
            <div class="sidebar-brand-icon">
                <img class="img-fluid img-thumbnail logo" src="{{asset('site/img/logo.png')}}" alt="logo">
            </div>
            <div class="sidebar-brand-text mx-3">Admin</div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item @if(request()->is('admin')) active @endif">
            <a class="nav-link" href="{{route('admin.dashboard')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Аукционы</span></a>
        </li>
        <li class="nav-item @if(request()->is('admin/products') || request()->is('admin/products/*')) active @endif">
            <a class="nav-link" href="{{route('admin.products.index')}}">
                <i class="fas fa-fw fa-cog"></i>
                <span>Каталог</span>
            </a>
        </li>
        <li class="nav-item @if(request()->is('admin/users')) active @endif">
            <a class="nav-link" href="{{route('admin.users.index')}}">
                <i class="fas fa-fw fa-user-alt"></i>
                <span>Пользователи</span></a>
        </li>
        <li class="nav-item @if(request()->is('admin/bots')) active @endif">
            <a class="nav-link" href="{{route('admin.bots.index')}}">
                <i class="fas fa-fw fa-robot"></i>
                <span>Боты</span></a>
        </li>
        <!-- Nav Item - Utilities Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link @if(!is_null(request()->route()) && request()->route()->getPrefix()!=='admin/settings') collapsed @endif"
               href="#"
               data-toggle="collapse" data-target="#collapseUtilities"
               aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-fw fa-wrench"></i>
                <span>Настройки</span>
            </a>
            <div id="collapseUtilities"
                 class="collapse @if(!is_null(request()->route()) && request()->route()->getPrefix()==='admin/settings') show @endif"
                 aria-labelledby="headingUtilities"
                 data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    {{--                        <a class="collapse-item" href="utilities-color.html">Colors</a>--}}
                    {{--                        <a class="collapse-item" href="utilities-border.html">Borders</a>--}}
                    <a class="collapse-item @if(request()->is('admin/settings/mailing')) active @endif"
                       href="{{route('admin.settings.mailing')}}">Рассылки</a>

                    <div class="collapse-divider"></div>
                    <h6 class="collapse-header">Другие:</h6>
                    <a class="collapse-item @if(request()->is('admin/settings/mail')) active @endif"
                       href="{{route('admin.settings.mail')}}">Настройки Е-майл</a>
                    <a class="collapse-item @if(request()->is('admin/settings/site')) active @endif"
                       href="{{route('admin.settings.site')}}">Настройки сайта</a>
                </div>
            </div>
        </li>

        <!-- Divider -->

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link @if(!is_null(request()->route()) && request()->route()->getPrefix()!=='admin/pages') collapsed @endif"
               href="#"
               data-toggle="collapse" data-target="#collapsePages"
               aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Страницы</span>
            </a>
            <div id="collapsePages"
                 class="collapse @if(!is_null(request()->route()) && request()->route()->getPrefix()==='admin/pages') show @endif"
                 aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item @if(request()->is('admin/pages/home')) active @endif"
                       href="{{route('admin.pages.home')}}">Главная</a>
                    <a class="collapse-item @if(request()->is('admin/pages/howitworks')) active @endif"
                       href="{{route('admin.pages.howitworks')}}">Как это работает</a>
                    <a class="collapse-item @if(request()->is('admin/pages/reviews')) active @endif"
                       href="{{route('admin.pages.reviews')}}">Отзывы</a>
                    <a class="collapse-item @if(request()->is('admin/pages/feedback')) active @endif"
                       href="{{route('admin.pages.feedback')}}">Обратная связь</a>
                    <a class="collapse-item @if(request()->is('admin/pages/coupon')) active @endif"
                       href="{{route('admin.pages.coupon')}}">Пополнить баланс</a>
                    <a class="collapse-item @if(request()->is('admin/pages/order')) active @endif"
                       href="{{route('admin.pages.order')}}">Оформление заказа</a>
                    <div class="collapse-divider"></div>
                    <h6 class="collapse-header">Другие страницы:</h6>
                    <a class="collapse-item @if(request()->is('admin/pages/footer')) active @endif"
                       href="{{route('admin.pages.footer')}}">Подвал сайта</a>
                </div>
            </div>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                <!-- Topbar Navbar -->
                @auth
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span
                                class="mr-2 d-none d-lg-inline text-gray-600 small">{{auth()->user()->nickname}}</span>
                                <img class="img-profile rounded-circle" src="{{auth()->user()->avatar()}}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                 aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{route('admin.profile')}}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Профиль
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Выйти
                                </a>

                            </div>
                        </li>

                    </ul>
                @endauth

            </nav>
            <!-- Notify -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End Notify -->

            <!-- End of Topbar -->

            <!-- preloader -->
            <div class="preloader-content">
                <div class="preloader">
                    <div class="📦"></div>
                    <div class="📦"></div>
                    <div class="📦"></div>
                    <div class="📦"></div>
                    <div class="📦"></div>
                </div>
            </div>
            <!-- End of preloader -->

            <!-- Begin Page Content -->

        @yield('content')
        <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; {{config('app.name')}} {{date('Y')}}</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Готовы уходить?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Выберите «Выход из системы» ниже, если вы готовы завершить текущий сеанс.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Отмена</button>
                <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Выход из
                    системы</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-sm" id="resourceModal" tabindex="-1" role="dialog"
     aria-labelledby="resourceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <form action="" method="post" id="resource-delete" enctype="multipart/form-data">
            @method('DELETE')
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resourceModalLabel">Удалить</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Вы уверены ?</h3>
                </div>
                <div class="modal-footer">
                    <div class="btn-group w-100" role="group" aria-label="Basic example">
                        <button type="button" data-dismiss="modal" class="btn btn-secondary btn-icon-split">
                        <span class="icon text-white-50">
                          <i class="fas fa-times-circle"></i>
                        </span>
                            <span class="text">Нет</span>
                        </button>
                        <button type="submit" class="btn btn-danger btn-icon-split">
                        <span class="icon text-white-50">
                          <i class="fas fa-trash"></i>
                        </span>
                            <span class="text">Да</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{asset('administration/js/jquery.min.js')}}"></script>
<script src="{{asset('administration/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('administration/js/jquery.easing.min.js')}}"></script>
<script src="{{asset('administration/js/sb-admin-2.min.js')}}"></script>
<script src="{{asset('administration/js/Chart.min.js')}}"></script>
<!-- Page level plugins -->
<script src="{{asset('datatables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('datatables/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('site/js/imask.js')}}"></script>
<!-- customs -->
<script src="{{asset('administration/js/customs.js')}}"></script>

@stack('js')
</body>
</html>
