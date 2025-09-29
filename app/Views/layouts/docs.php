<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Brick Framework Dokumentation - Moderne PHP Webanwendungen">
    
    <title>@yield('title', 'Dokumentation') - Brick Framework</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Prism.js für Code-Highlighting -->
    <link href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Dynamically injected CSS will appear here -->
    
    <style>
        .docs-sidebar {
            position: sticky;
            top: 20px;
            height: calc(100vh - 40px);
            overflow-y: auto;
        }
        
        .docs-nav .nav-link {
            color: #6c757d;
            border-radius: 5px;
            margin-bottom: 2px;
        }
        
        .docs-nav .nav-link:hover,
        .docs-nav .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        
        .docs-content {
            min-height: calc(100vh - 200px);
        }
        
        .code-block {
            background: #2d3748;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .docs-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        
        .breadcrumb {
            background: none;
            padding: 0;
        }
        
        .table-of-contents {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .example-block {
            border-left: 4px solid #007bff;
            background: #f8f9fa;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .warning-block {
            border-left: 4px solid #ffc107;
            background: #fff3cd;
            padding: 1rem;
            margin: 1rem 0;
        }
        
        .info-block {
            border-left: 4px solid #17a2b8;
            background: #d1ecf1;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    <!-- Documentation Header -->
    <header class="docs-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h4 mb-0">
                        <a href="/docs" class="text-white text-decoration-none">
                            🧱 Brick Framework Docs
                        </a>
                    </h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-md-end mb-0">
                            <li class="breadcrumb-item"><a href="/" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item"><a href="/docs" class="text-white-50">Docs</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">@yield('title')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            <nav class="col-md-3 col-lg-2 docs-sidebar bg-light p-3">
                <div class="nav nav-pills flex-column docs-nav">
                    <h6 class="text-muted text-uppercase fw-bold mb-2">Framework</h6>
                    <a class="nav-link" href="/docs">📖 Übersicht</a>
                    <a class="nav-link" href="/docs/overview">🧱 Framework Info</a>
                    <a class="nav-link" href="/docs/installation">⚙️ Installation</a>
                    
                    <h6 class="text-muted text-uppercase fw-bold mb-2 mt-3">Entwicklung</h6>
                    <a class="nav-link" href="/docs/routing">🛣️ Routing</a>
                    <a class="nav-link" href="/docs/views">🎨 Views & Templates</a>
                    <a class="nav-link" href="/docs/css-js">💎 CSS/JS Assets</a>
                    <a class="nav-link" href="/docs/middleware">🔄 Middleware</a>
                    <a class="nav-link" href="/docs/database">🗄️ Database</a>
                    
                    <h6 class="text-muted text-uppercase fw-bold mb-2 mt-3">Referenz</h6>
                    <a class="nav-link" href="/docs/api">📚 API Referenz</a>
                    
                    <h6 class="text-muted text-uppercase fw-bold mb-2 mt-3">Beispiele</h6>
                    <a class="nav-link" href="/demo/css-js">🎯 CSS/JS Demo</a>
                    <a class="nav-link" href="/demo/templates">🎨 Template Demo</a>
                </div>
            </nav>
            
            <!-- Documentation Content -->
            <main class="col-md-9 col-lg-10 docs-content p-4">
                @yield('content')
                
                <!-- Page Navigation -->
                <div class="row mt-5 pt-4 border-top">
                    <div class="col-6">
                        @yield('prev-page')
                    </div>
                    <div class="col-6 text-end">
                        @yield('next-page')
                    </div>
                </div>
            </main>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; 2025 Brick Framework. Alle Rechte vorbehalten.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">
                        <a href="https://github.com/bitka-de/brick-framework" class="text-decoration-none text-light">
                            <i class="fab fa-github"></i> GitHub Repository
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Prism.js für Code-Highlighting -->
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-core.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>
    
    <!-- Dynamically injected JS will appear here -->
    
    <script>
        // Dokumentations-spezifische Funktionalität
        document.addEventListener('DOMContentLoaded', function() {
            // Aktuelle Seite in Navigation markieren
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.docs-nav .nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
            
            // Smooth Scrolling für Anker-Links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>