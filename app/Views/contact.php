@extends('app')

@section('title', 'Kontakt - Brick Framework')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h1 class="mb-4">Kontakt</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">📧 Kontakt-Information</h5>
                        
                        <div class="mb-3">
                            <strong>E-Mail:</strong><br>
                            <a href="mailto:jp@bitka.de">jp@bitka.de</a>
                        </div>
                        
                        <div class="mb-3">
                            <strong>GitHub:</strong><br>
                            <a href="https://github.com/bitka-de/brick-framework" target="_blank">
                                github.com/bitka-de/brick-framework
                            </a>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Website:</strong><br>
                            <a href="https://bitka.de" target="_blank">bitka.de</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">🚀 Framework-Status</h5>
                        
                        <div class="mb-3">
                            <strong>Version:</strong> v1.0.0
                        </div>
                        
                        <div class="mb-3">
                            <strong>PHP Version:</strong> {{ PHP_VERSION }}
                        </div>
                        
                        <div class="mb-3">
                            <strong>Status:</strong> 
                            <span class="badge bg-success">Aktiv</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Tests:</strong> 
                            <span class="badge bg-success">91 bestanden</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-5">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">🧱 Über das Brick Framework</h5>
                    <p class="card-text">
                        Das Brick Framework ist ein modernes, leichtgewichtiges PHP-Framework, 
                        das für Geschwindigkeit, Einfachheit und Entwicklerfreundlichkeit entwickelt wurde.
                    </p>
                    
                    <div class="row text-center">
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-primary">🚀</h3>
                                <small>Hochperformant</small>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-success">🎨</h3>
                                <small>Template-Engine</small>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-warning">🛡️</h3>
                                <small>Sicher</small>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h3 class="text-info">🧪</h3>
                                <small>Gut getestet</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="/" class="btn btn-primary">← Zurück zur Startseite</a>
            <a href="/about" class="btn btn-outline-primary">Über uns</a>
        </div>
    </div>
</div>
@endsection