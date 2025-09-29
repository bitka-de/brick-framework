@extends('docs')

@section('title', 'Brick Framework Dokumentation')

@css
.doc-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.doc-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.doc-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

.feature-highlight {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem 0;
}
@endcss

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-primary text-white text-center py-5 mb-5">
                <div class="container">
                    <h1 class="display-4">🧱 Brick Framework</h1>
                    <p class="lead">Modernes PHP Web Framework mit Blade-ähnlichen Templates</p>
                    <hr class="my-4">
                    <p>Vollständige Dokumentation für Entwickler</p>
                    <a class="btn btn-light btn-lg" href="#getting-started" role="button">Jetzt starten</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div id="getting-started" class="mb-5">
                <h2>🚀 Schnellstart</h2>
                <p class="lead">Das Brick Framework ist ein modernes PHP Web Framework, das entwickelt wurde, um die Entwicklung von Webanwendungen zu vereinfachen.</p>
                
                <div class="feature-highlight">
                    <h4>✨ Hauptfeatures</h4>
                    <ul class="list-unstyled">
                        <li>🎨 <strong>Blade-ähnliches Template System</strong> - Vertraute Syntax mit @extends, @section, @include</li>
                        <li>💎 <strong>CSS/JS Asset Management</strong> - @css und @js Direktiven für optimierte Asset-Einbindung</li>
                        <li>🛣️ <strong>Flexibles Routing</strong> - Einfache Route-Definitionen mit Closures und Controllern</li>
                        <li>🔄 <strong>Middleware Support</strong> - Request/Response-Pipeline für Cross-Cutting Concerns</li>
                        <li>📱 <strong>Responsive Design</strong> - Bootstrap-Integration für moderne UIs</li>
                    </ul>
                </div>
            </div>
            
            <h2>📖 Dokumentations-Bereiche</h2>
            <div class="row">
                @foreach($sections as $key => $section)
                <div class="col-md-6 mb-4">
                    <div class="card doc-card h-100">
                        <div class="card-body text-center">
                            <div class="doc-icon">{{ $section['icon'] }}</div>
                            <h5 class="card-title">{{ $section['title'] }}</h5>
                            <p class="card-text">{{ $section['description'] }}</p>
                            <a href="/docs/{{ $key }}" class="btn btn-primary">Mehr erfahren</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>🔗 Quick Links</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="/docs/installation">⚙️ Installation</a></li>
                        <li class="list-group-item"><a href="/docs/routing">🛣️ Routing</a></li>
                        <li class="list-group-item"><a href="/docs/views">🎨 Templates</a></li>
                        <li class="list-group-item"><a href="/docs/css-js">💎 CSS/JS Assets</a></li>
                        <li class="list-group-item"><a href="/demo/css-js">🎯 Live Demo</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>📊 Framework Info</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr><td><strong>Version:</strong></td><td>{{ $version }}</td></tr>
                        <tr><td><strong>PHP Version:</strong></td><td>8.0+</td></tr>
                        <tr><td><strong>License:</strong></td><td>MIT</td></tr>
                        <tr><td><strong>Repository:</strong></td><td><a href="https://github.com/bitka-de/brick-framework">GitHub</a></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection