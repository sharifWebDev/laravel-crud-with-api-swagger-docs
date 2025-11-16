<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home-Admin Dashboard, a default title')</title>
    <meta name="keywords" content="@yield('meta_keywords', 'some default keywords')">
    <meta name="title" content="@yield('meta_title', 'default meta title')">
    <meta name="description" content="@yield('meta_description', 'default description')">
    <link rel="canonical" href="{{ url()->current() }}" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/custom_style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet"
        href="{{ asset('system_builder/public/storage/test_ecommerce/public/backend/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/sweetalert@1.0.1/dist/sweetalert.css" />
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css" />
    @stack('.css')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper"> 
        @include('layouts.partials.navbar')
        @include('layouts.partials.sidebar')
        <style>
            body {
                font-family: "Popins", sans-serif;
            }

            .breadcrumb,
            .button,
            .page-headder {
                margin: auto !important;
            }

            a#view,
            #edit,
            #delete {
                width: 29px !important;
                height: 24px !important;
                padding: 2px !important;
            }

            table tr th:last-child,
            table tr td:last-child {
                text-align: center !important;
            }

            #delete {
                margin-right: 0px !important
            }

            #loaderContent {
                z-index: 10000;
                position: fixed;
                /* changed to fixed to center on page */
                top: 50%;
                /* position it vertically at 50% from the top */
                left: 50%;
                /* position it horizontally at 50% from the left */
                transform: translate(-50%, -50%);
                /* move it back by half of its own width and height */
            }

            .content,
            #loaderContent {
                transition: opacity 0.5s ease;
            }

            /* Initially hide content */
            .content {
                opacity: 0;
            }

            /* Initially show loader */
            #loaderContent {
                opacity: 1;
            }
            .form-switch .form-check-input {
                width: 4em !important;
            }
        </style>
        <div class="py-3 content-wrapper">
            <div class="px-4">
                <section class="content">
                    <div id="loaderContent">
                        <div class="spinner-border text-success" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="container-fluid">
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </section>
            </div>
        </div>
        @include('layouts.partials.footer')
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script src="{{ asset('backend/dist/js/adminlte.js') }}"></script>
    <script src="{{ asset('backend/dist/js/demo.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.4/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // ✅ Page Loader
        document.onreadystatechange = () => {
            const content = document.querySelector(".content");
            const loader = document.querySelector("#loaderContent");

            if (document.readyState !== "complete") {
                content.style.opacity = "0";
                loader.style.opacity = "1";
            } else {
                loader.style.opacity = "0";
                content.style.opacity = "1";
            }
        };
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.querySelector('#datatable tbody');

            if (tableBody) {
                tableBody.innerHTML = `
                <tr id="loading-row">
                    <td colspan="100%" class="py-5 text-center">
                        <i class="fas fa-spinner fa-spin fa-3x text-success"></i>
                        <div>Loading data...</div>
                    </td>
                </tr>
            `;
            }
        });
        // fetchDataAndPopulateTable();
        var toggleSwitch = document.querySelector('.theme-switch input[type="checkbox"]');
        var currentTheme = localStorage.getItem('theme');
        var mainHeader = document.querySelector('.main-header');

        if (currentTheme) {
            if (currentTheme === 'dark') {
                if (!document.body.classList.contains('dark-mode')) {
                    document.body.classList.add("dark-mode");
                }
                if (mainHeader.classList.contains('navbar-light')) {
                    mainHeader.classList.add('navbar-dark');
                    mainHeader.classList.remove('navbar-light');
                }
                toggleSwitch.checked = true;
            }
        }

        function switchTheme(e) {
            if (e.target.checked) {
                if (!document.body.classList.contains('dark-mode')) {
                    document.body.classList.add("dark-mode");
                }
                if (mainHeader.classList.contains('navbar-light')) {
                    mainHeader.classList.add('navbar-dark');
                    mainHeader.classList.remove('navbar-light');
                }
                localStorage.setItem('theme', 'dark');
            } else {
                if (document.body.classList.contains('dark-mode')) {
                    document.body.classList.remove("dark-mode");
                }
                if (mainHeader.classList.contains('navbar-dark')) {
                    mainHeader.classList.add('navbar-light');
                    mainHeader.classList.remove('navbar-dark');
                }
                localStorage.setItem('theme', 'light');
            }
        }

 
        // ✅ Delete link (.delete class)
        document.addEventListener('click', async (e) => {
            if (e.target.classList.contains('delete')) {
                e.preventDefault();
                const dataUrl = e.target.getAttribute('href');
                const row = e.target.closest('tr');

                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#029b47',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                });

                if (result.isConfirmed) {
                    try {
                        const res = await axios.delete(dataUrl);
                        if (res.data.success) {
                             Swal.fire({
                                title: 'Deleted!',
                                text: 'The record has been removed.',
                                icon: 'success',
                                timer: 2000,             // 2000ms = 2 seconds
                                showConfirmButton: false, // hide the OK button
                                willClose: () => {
                                    // Optional: code to run after alert closes
                                }
                            });
                            if ($.fn.DataTable.isDataTable('.table')) {
                                $('.table').DataTable().ajax.reload(null, false);
                            }
                        } else {
                            Swal.fire('Failed', res.data.message || 'Deletion failed', 'error');
                        }
                    } catch (err) {
                        Swal.fire('Error', err.response?.data?.message || err.message, 'error');
                    }
                } else {
                    Swal.fire('Cancelled', 'No changes made.', 'info');
                }
            }
        });

        // ✅ Status Toggle Handler
        document.addEventListener('change', async (e) => {
            if (e.target.matches('.status-toggle, .badge-status')) {
                e.preventDefault();

                const checkbox = e.target;
                const dataId = checkbox.dataset.id;
                const columnName = checkbox.name;
                const newStatus = checkbox.checked ? 1 : 0;
                const route = `{{ route('admin.data.toggleStatus', ':id') }}`.replace(':id', dataId);

                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: `Change status to ${newStatus ? 'Active' : 'In-Active'}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, change it!',
                    cancelButtonText: 'Cancel'
                });

                if (!result.isConfirmed) {
                    checkbox.checked = !checkbox.checked;
                    Swal.fire('Cancelled', 'No changes made.', 'info');
                    return;
                }

                try {
                    const res = await axios.post(route, {
                        status: newStatus,
                        url: window.location.href,
                        columnName: columnName
                    });

                    if (res.data.success) {
                        Swal.fire('Updated!', res.data.message, 'success');
                        const badge = checkbox.closest('tr')?.querySelector('.status-badge');
                        if (badge) {
                            badge.className =
                                `badge status-badge ${newStatus ? 'active-status' : 'inactive-status'}`;
                            badge.textContent = newStatus ? 'Active' : 'In-Active';
                        }
                    } else {
                        Swal.fire('Failed', res.data.message || 'Update failed', 'error');
                        checkbox.checked = !checkbox.checked;
                    }
                } catch (error) {
                    Swal.fire('Error', error.response?.data?.message || 'Failed to update status', 'error');
                    checkbox.checked = !checkbox.checked;
                }
            }
        });
        </script>

        <script src="{{ asset('backend/datatables/datatables-renderers.js') }}"></script>
        <script src="{{ asset('backend/datatables/datatables-init.js') }}"></script>
        <script src="{{ asset('backend/datatables/datatables-core.js') }}"></script>
    </script>
    @stack('.js')
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif 
</body>

</html>
