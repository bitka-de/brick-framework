<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Brick Framework - Moderne PHP Webanwendung')">
    
    <title>@yield('title', 'Brick Framework')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    @yield('styles')
    
    <!-- Dynamically injected CSS will appear here -->
    
    <style>
        .navbar-brand {
            font-weight: bold;
        }
        .footer {
            margin-top: auto;
            padding: 20px 0;
            background-color: #f8f9fa;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                🧱 Brick Framework
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Über uns</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Kontakt</a>
                    </li>
                    @yield('navigation')
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="content-wrapper">
        <!-- Breadcrumb -->
        @yield('breadcrumb')
        
        <!-- Flash Messages -->
        @if(isset($flash_message))
            <div class="container mt-3">
                <div class="alert alert-{{ $flash_type ?? 'info' }} alert-dismissible fade show" role="alert">
                    {{ $flash_message }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        <!-- Page Header -->
        @yield('header')
        
        <!-- Page Content -->
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} Brick Framework. Alle Rechte vorbehalten.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        Powered by 
                        <a href="https://github.com/bitka-de/brick-framework" class="text-decoration-none">
                            🧱 Brick Framework
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    @yield('scripts')
    
    <script>
        // Brick Framework Client-Side Utilities
        window.Brick = {
            // AJAX Helper
            request: function(url, options = {}) {
                return fetch(url, {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        ...options.headers
                    },
                    ...options
                });
            },
            
            // Flash Message Helper
            flash: function(message, type = 'info') {
                const container = document.querySelector('.container');
                if (container) {
                    const alert = document.createElement('div');
                    alert.className = `alert alert-${type} alert-dismissible fade show mt-3`;
                    alert.innerHTML = `
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    container.insertBefore(alert, container.firstChild);
                    
                    // Auto-remove nach 5 Sekunden
                    setTimeout(() => alert.remove(), 5000);
                }
            }
        };
        
        // Development Helper
        // @if($debug ?? false)
        // console.log('🧱 Brick Framework Debug Mode');
        // console.log('View Stats:', @json($__view->getStats() ?? []));
        // @endif
    </script>
</body>
</html>