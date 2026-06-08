<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Informasi Stok Gudang Toko Plastik">
    <title>@yield('title', 'Dashboard') | Sistem Gudang Toko Plastik</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Theme Styles -->
    <style>
        :root {
            --font-family: 'Outfit', sans-serif;
            --primary-color: #10b981; /* Emerald green */
            --primary-hover: #059669;
            --primary-light: #ecfdf5;
            --secondary-color: #0ea5e9; /* Sky blue */
            --dark-color: #0f172a; /* Slate 900 */
            --sidebar-color: #1e293b; /* Slate 800 */
            --bg-color: #f8fafc; /* Slate 50 */
            --card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06), 0 2px 10px -1px rgba(15, 23, 42, 0.04);
            --sidebar-width: 260px;
            --header-height: 70px;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-color);
            color: #334155;
            overflow-x: hidden;
        }

        /* Layout Structure */
        #wrapper {
            display: flex;
            width: 100vw;
            min-height: 100vh;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background-color: var(--sidebar-color);
            color: #94a3b8;
            transition: all 0.3s ease-in-out;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(15, 23, 42, 0.05);
        }

        #sidebar .brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid #334155;
            background: linear-gradient(135deg, #10b981 -20%, #1e293b 80%);
        }

        #sidebar .brand h5 {
            color: #ffffff;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #sidebar .menu-list {
            padding: 20px 10px;
            flex-grow: 1;
            overflow-y: auto;
        }

        #sidebar .menu-list::-webkit-scrollbar {
            width: 5px;
        }
        #sidebar .menu-list::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }

        #sidebar .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #64748b;
            padding: 10px 15px 5px;
            font-weight: 700;
        }

        #sidebar .menu-item {
            display: flex;
            align-items: center;
            padding: 11px 15px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 4px;
            font-weight: 500;
            transition: all 0.2s ease;
            gap: 12px;
        }

        #sidebar .menu-item i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            transition: all 0.2s ease;
        }

        #sidebar .menu-item:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
        }

        #sidebar .menu-item.active {
            color: #ffffff;
            background-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
        }

        #sidebar .menu-item.active i {
            color: #ffffff;
        }

        #sidebar .sidebar-footer {
            padding: 15px;
            border-top: 1px solid #334155;
            background-color: #0f172a;
        }

        #sidebar .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #sidebar .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
            background-color: #cbd5e1;
        }

        #sidebar .user-info .details {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        #sidebar .user-info .name {
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
        }

        #sidebar .user-info .role {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: capitalize;
        }

        /* Page Content Structure */
        #content-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100vw - var(--sidebar-width));
            transition: all 0.3s ease-in-out;
        }

        /* Header / Navbar Styling */
        #header {
            height: var(--header-height);
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        #header .toggle-btn {
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        #header .toggle-btn:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }

        /* Dropdown custom badges */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 8px;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
        }

        /* Notification Panel Dropdown */
        .notification-dropdown {
            width: 320px;
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Content Area */
        #main-content {
            padding: 24px;
            flex-grow: 1;
        }

        /* Card and Table Beautification */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 16px -6px rgba(15, 23, 42, 0.06);
        }

        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 15px 20px;
            border-bottom-width: 1px;
        }

        .table td {
            padding: 15px 20px;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        /* Primary Button */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 16px;
            transition: all 0.2s;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--primary-hover) !important;
            border-color: var(--primary-hover) !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25) !important;
        }

        /* Badge Custom styling */
        .badge-pill {
            padding: 0.35em 0.8em;
            font-weight: 600;
            border-radius: 30px;
        }

        /* Footer styling */
        #footer {
            height: 50px;
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 0.8rem;
        }

        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            #sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
                position: fixed;
                height: 100vh;
            }

            #content-wrapper {
                width: 100vw;
            }

            #wrapper.sidebar-toggled #sidebar {
                margin-left: 0;
            }

            #wrapper.sidebar-toggled #content-wrapper {
                margin-left: var(--sidebar-width);
                width: calc(100vw - var(--sidebar-width));
            }
        }
        
        /* Micro-interactions & animations */
        .btn-animate {
            transition: all 0.2s ease-in-out;
        }
        .btn-animate:hover {
            transform: scale(1.03);
        }
        .fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    @php
        // Fetch low stock products to display real-time warnings in the layout header dropdown
        $globalLowStockProducts = \App\Models\Product::whereRaw('stok <= minimum_stok')->limit(5)->get();
        $globalLowStockCount = \App\Models\Product::whereRaw('stok <= minimum_stok')->count();
    @endphp

    <div id="wrapper">
        <!-- Sidebar Navigation -->
        <aside id="sidebar">
            <div class="brand">
                <h5>
                    <i class="fa-solid fa-boxes-packing text-primary"></i> 
                    <span>PLASTIK GUDANG</span>
                </h5>
            </div>
            
            <nav class="menu-list">
                <a href="{{ route('dashboard') }}" class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="section-title">Master Data</div>
                
                <a href="{{ route('categories.index') }}" class="menu-item {{ Route::is('categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i>
                    <span>Kategori Produk</span>
                </a>
                
                <a href="{{ route('suppliers.index') }}" class="menu-item {{ Route::is('suppliers.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-field"></i>
                    <span>Supplier</span>
                </a>
                
                <a href="{{ route('products.index') }}" class="menu-item {{ Route::is('products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box-archive"></i>
                    <span>Produk & Stok</span>
                </a>
                
                <div class="section-title">Transaksi Gudang</div>
                
                <a href="{{ route('barang-masuk.index') }}" class="menu-item {{ Route::is('barang-masuk.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-arrow-down text-emerald-500"></i>
                    <span>Barang Masuk</span>
                </a>
                
                <a href="{{ route('barang-keluar.index') }}" class="menu-item {{ Route::is('barang-keluar.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-circle-arrow-up text-rose-500"></i>
                    <span>Barang Keluar</span>
                </a>
                
                <div class="section-title">Analitik</div>
                
                <a href="{{ route('reports.index') }}" class="menu-item {{ Route::is('reports.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span>Laporan Stok</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-info">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&bold=true" alt="Avatar User">
                    <div class="details">
                        <span class="name">{{ Auth::user()->name }}</span>
                        <span class="role">{{ Auth::user()->role }}</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Page Content Wrapper -->
        <div id="content-wrapper">
            <!-- Header Navigation -->
            <header id="header">
                <div class="d-flex align-items-center gap-3">
                    <button id="sidebarToggle" class="toggle-btn">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <nav aria-label="breadcrumb" class="d-none d-md-block m-0">
                        <ol class="breadcrumb m-0" style="font-size: 0.9rem;">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Home</a></li>
                            <li class="breadcrumb-item active text-slate-800 fw-medium" aria-current="page">@yield('breadcrumb', 'Dashboard')</li>
                        </ol>
                    </nav>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <!-- Notifications Dropdown (Stok Menipis) -->
                    <div class="dropdown">
                        <button class="toggle-btn position-relative" type="button" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-bell"></i>
                            @if($globalLowStockCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                                    {{ $globalLowStockCount }}
                                </span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notifDropdown">
                            <div class="px-3 py-2 fw-bold border-bottom d-flex justify-content-between align-items-center">
                                <span>Notifikasi Stok</span>
                                <span class="badge bg-danger rounded-pill">{{ $globalLowStockCount }} Tipis</span>
                            </div>
                            
                            @if($globalLowStockCount > 0)
                                @foreach($globalLowStockProducts as $notifProduct)
                                    <a href="{{ route('products.index', ['search' => $notifProduct->kode_produk]) }}" class="dropdown-item notification-item text-wrap text-decoration-none">
                                        <div class="notification-icon bg-danger bg-opacity-10 text-danger">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark" style="font-size: 0.85rem;">Stok {{ $notifProduct->nama_produk }} Menipis!</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">Stok saat ini tinggal <strong>{{ $notifProduct->stok }} {{ $notifProduct->satuan }}</strong> (Min: {{ $notifProduct->minimum_stok }})</div>
                                        </div>
                                    </a>
                                @endforeach
                                <div class="p-2 border-top text-center">
                                    <a href="{{ route('products.index') }}" class="text-primary text-decoration-none fw-semibold" style="font-size: 0.8rem;">Lihat Semua Produk</a>
                                </div>
                            @else
                                <div class="text-center p-4 text-muted">
                                    <i class="fa-regular fa-bell-slash d-block fs-3 mb-2 text-slate-300"></i>
                                    <span style="font-size: 0.85rem;">Semua stok aman & tersedia!</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- User Actions Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-light bg-white border dropdown-toggle d-flex align-items-center gap-2 py-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 8px;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&bold=true" alt="Avatar" width="24" height="24" class="rounded-circle">
                            <span class="d-none d-sm-inline" style="font-size: 0.85rem; font-weight: 500;">{{ Auth::user()->name }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><h6 class="dropdown-header">Hak Akses: <span class="badge bg-emerald-500 text-dark text-uppercase bg-emerald-100">{{ Auth::user()->role }}</span></h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        <span>Keluar / Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Main Page Content -->
            <main id="main-content" class="fade-in-up">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer id="footer">
                <span>&copy; {{ date('Y') }} Sistem Gudang Toko Plastik. Crafted for Excellence.</span>
            </footer>
        </div>
    </div>

    <!-- Scripts JQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- General Application JS -->
    <script>
        $(document).ready(function() {
            // Toggle Sidebar click event
            $('#sidebarToggle').on('click', function(e) {
                e.preventDefault();
                $('#wrapper').toggleClass('sidebar-toggled');
            });

            // Initialize general dataTables
            if ($('.datatable').length > 0) {
                $('.datatable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                    },
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
                });
            }
            
            // Auto trigger alerts from Session flash
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    text: "{{ session('error') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif
        });

        // Safe Delete confirmation popup
        function confirmDelete(id, text = 'Data ini akan dihapus permanen!') {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + id).submit();
                }
            });
        }
    </script>
    
    @yield('scripts')
</body>
</html>
