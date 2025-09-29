@extends('app')

@section('title', 'CSS & JavaScript Demo')

@css('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css')

@css
.demo-box {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 10px;
    margin: 1rem 0;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.pulse-button {
    background: #ff6b6b;
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pulse-button:hover {
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
}
@endcss

@js('https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js')
@js('https://unpkg.com/sweetalert/dist/sweetalert.min.js')

@js
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 CSS & JS Demo geladen!');
    
    if (typeof gsap !== 'undefined') {
        gsap.from('.demo-box', {
            duration: 1.2,
            y: 50,
            opacity: 0,
            stagger: 0.2,
            ease: 'power2.out'
        });
    }
    
    const pulseBtn = document.getElementById('pulseBtn');
    if (pulseBtn) {
        pulseBtn.addEventListener('click', function() {
            if (typeof swal !== 'undefined') {
                swal("Wow!", "Die CSS & JS Direktiven funktionieren perfekt!", "success");
            } else {
                alert('CSS & JS Direktiven funktionieren!');
            }
        });
    }
});
@endjs

@section('content')
<div class="container">
    <h1 class="animate__animated animate__fadeInDown">🎨 CSS & JavaScript Direktiven Demo</h1>
    <p class="lead animate__animated animate__fadeInUp">
        Diese Seite demonstriert die neuen CSS- und JavaScript-Direktiven des Brick View Systems.
    </p>
    
    <div class="demo-box">
        <h3>📦 Demo Box 1</h3>
        <p>Diese Box verwendet Inline CSS-Styling aus der @css Direktive.</p>
        <p>Gradient-Hintergrund, Schatten und Animationen werden automatisch in den Header eingefügt.</p>
    </div>
    
    <div class="demo-box">
        <h3>⚡ Demo Box 2</h3>
        <p>GSAP-Animation wird beim Laden der Seite ausgeführt.</p>
        <p>Die externe JavaScript-Bibliothek wurde mit @js('gsap.min.js') geladen.</p>
    </div>
    
    <div class="demo-box">
        <h3>🎯 Demo Box 3</h3>
        <p>SweetAlert wird für schöne Popup-Nachrichten verwendet.</p>
        <button id="pulseBtn" class="pulse-button">
            Klick mich! 🎉
        </button>
    </div>
    
    <div class="mt-5 p-4 bg-light rounded">
        <h4>📋 Verwendete Direktiven:</h4>
        <div class="row">
            <div class="col-md-6">
                <h6>CSS-Direktiven:</h6>
                <ul class="list-unstyled">
                    <li><code>@css('animate.css')</code> - Externe CSS-Datei</li>
                    <li><code>@css ... @endcss</code> - Inline CSS-Styles</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>JavaScript-Direktiven:</h6>
                <ul class="list-unstyled">
                    <li><code>@js('gsap.min.js')</code> - Externe JS-Datei</li>
                    <li><code>@js('sweetalert.min.js')</code> - SweetAlert Bibliothek</li>
                    <li><code>@js ... @endjs</code> - Inline JavaScript</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="/" class="btn btn-primary">← Zurück zur Startseite</a>
        <a href="/demo/templates" class="btn btn-outline-secondary">Template Demo</a>
        <a href="/test/css" class="btn btn-outline-info">CSS Test</a>
        <a href="/test/js" class="btn btn-outline-warning">JS Test</a>
    </div>
</div>
@endsection