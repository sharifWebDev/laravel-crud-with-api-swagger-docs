<!-- Navbar -->
<nav class="px-4 py-2 main-header navbar navbar-expand navbar-white navbar-light border-top">
    <!-- Left navbar links -->
    <ul class="py-1 navbar-nav">
        <li class="px-0 nav-item">
            <a class="px-0 nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="/" class="nav-link goweb" target="_blank"><i class="fa-solid fa-earth-americas"></i></a>
        </li>
    </ul>


    <!-- Right navbar links -->
    <ul class="ml-auto navbar-nav align-items-center">  <li class="nav-item">
            <a id="optimize" class="mr-5 btn btn-info btn-sm" href="{{ url('/admin/artisan/optimize') }}">
                Optimize
            </a>
        </li>

        <script>
            document.getElementById('optimize').addEventListener('click', function(event) {
                event.preventDefault();

                const url = this.href;

                axios.get(url)
                    .then(function(response) {
                        toastr.success(response.data.message);
                    })
                    .catch(function(error) {
                        console.error('There was an error!', error);
                        alert('An error occurred. Check the console for details.');
                    });
            });

        </script>
        <!-- Messages Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="../backend/dist/img/user1-128x128.jpg" alt="User Avatar" class="mr-3 img-size-50 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Brad Diesel
                                <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">Call me whenever you can...</p>
                            <p class="text-sm text-muted"><i class="mr-1 far fa-clock"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="../backend/dist/img/user8-128x128.jpg" alt="User Avatar" class="mr-3 img-size-50 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                John Pierce
                                <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">I got your message bro</p>
                            <p class="text-sm text-muted"><i class="mr-1 far fa-clock"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <!-- Message Start -->
                    <div class="media">
                        <img src="../backend/dist/img/user3-128x128.jpg" alt="User Avatar" class="mr-3 img-size-50 img-circle">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                Nora Silvester
                                <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                            </h3>
                            <p class="text-sm">The subject goes here</p>
                            <p class="text-sm text-muted"><i class="mr-1 far fa-clock"></i> 4 Hours Ago</p>
                        </div>
                    </div>
                    <!-- Message End -->
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell text-secoundary"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="mr-2 fas fa-envelope"></i> 4 new messages
                    <span class="float-right text-sm text-muted">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="mr-2 fas fa-users"></i> 8 friend requests
                    <span class="float-right text-sm text-muted">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="mr-2 fas fa-file"></i> 3 new reports
                    <span class="float-right text-sm text-muted">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fa-solid fa-expand"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                <i class="fa-solid fa-gear"></i>
            </a>
        </li>


        <div class="btn-group">
            <button type="button" class="border btn" data-toggle="dropdown" data-display="static" aria-expanded="false">

                <span class="flex inline-flex rounded-md d-flex">
                    <img class="object-cover w-12 h-12 rounded-full" height="45px" src="{{ Auth::user()->profile_photo_url ?? "" }}" alt="{{ Auth::user()->name ?? "" }}">
                    <div class="ml-3 leading-tight">
                        <div class="text-gray-900">{{ Auth::user()->name ?? "" }}</div>
                        <div class="float-left text-sm text-gray-700">{{ Auth::user()->role ?? "" }}</div>
                    </div>
                    <span class="my-auto ml-4 fa fa-angle-down"></span>
                </span>
            </button>
            <ul class="float-right dropdown-menu user">
                <li>

                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Account') }}
                    </div>

                </li>
                <li>
                    <button class="dropdown-item px-auto" type="button">
                        <div class="border-gray-200 border-top"></div>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button class="ml-4 border-none btn btn-transparent" type="submit">Logout</button>
                        </form>
                    </button>
                </li>
            </ul>
        </div>


    </ul>
</nav>
<!-- /.navbar -->