<aside class="main-sidebar sidebar-dark-primary elevation-4" style="z-index:10000 !important">
    <div class="pb-4 mt-5 mb-4 user-panel d-flex align-items-center border-bottom border-dark">
        <a href="/" class="mt-2 bg-transparent brand-link img justify-content-center align-items-center">
            <span class="brand-text font-weight-light">Task</span>
        </a>
    </div>
    <div class="mt-0 sidebar">
        <div class="mt-0 mb-4 form-inline">
            <div class="bg-transparent input-group" data-widget="sidebar-search"> <input
                    class="bg-transparent form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append"> <button class="bg-transparent btn btn-sidebar"> <i
                            class="fas fa-search fa-fw"></i> </button> </div>
            </div>
            <div class="sidebar-search-results">
                <div class="list-group"><a href="#" class="list-group-item">
                        <div class="search-title"><strong class="text-light"> </strong>N<strong
                                class="text-light"></strong> o<strong class="text-light"></strong> <strong
                                class="text-light"></strong>e <strong class="text-light"></strong>l<strong
                                class="text-light"></strong>e<strong class="text-light"> </strong>m<strong
                                class="text-light"></strong>e<strong class="text-light"></strong>n <strong
                                class="text-light"></strong>t<strong class="text-light"></strong> <strong
                                class="text-light"></strong>f<strong class="text-light"></strong>o <strong
                                class="text-light"></strong>u<strong class="text-light"></strong>n <strong
                                class="text-light"></strong>d<strong class="text-light"></strong> !<strong
                                class="text-light"></strong> </div>
                        <div class="search-path"></div>
                    </a></div>
            </div>
        </div>
        <style>
            .nav-item a p {
                font-size: 12px !important;
                color: #f6f6f6;
            }
        </style>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-collapse-hide-child nav-child-indent nav-legacy"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('admin/dashboard') }}"
                        class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{ url('admin/students') }}"
                        class="nav-link {{ request()->is('admin/students*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-angle-double-right"></i>
                        <p>Students</p>
                    </a>
                </li>


            </ul>
        </nav> <!-- /.sidebar-menu -->
    </div> <!-- /.sidebar -->
</aside>
