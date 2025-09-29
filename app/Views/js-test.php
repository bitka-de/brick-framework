@extends('app')

@section('title', 'JavaScript Test')

@js('https://unpkg.com/sweetalert/dist/sweetalert.min.js')

@js
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎉 JavaScript Direktive funktioniert!');
    
    const button = document.getElementById('testBtn');
    if (button) {
        button.addEventListener('click', function() {
            if (typeof swal !== 'undefined') {
                swal("Erfolg!", "Die JavaScript-Direktiven funktionieren perfekt!", "success");
            } else {
                alert('JavaScript funktioniert!');
            }
        });
    }
});
@endjs

@section('content')
<div class="container">
    <h1>JavaScript Test</h1>
    <p class="lead">Diese Seite testet JavaScript-Direktiven.</p>
    
    <div class="alert alert-info">
        <strong>JavaScript geladen!</strong> Externe und Inline-JavaScript-Dateien wurden eingefügt.
    </div>
    
    <div class="text-center mt-4">
        <button id="testBtn" class="btn btn-primary btn-lg">
            🚀 Test JavaScript
        </button>
    </div>
    
    <div class="mt-4">
        <h5>📋 Verwendete JavaScript-Direktiven:</h5>
        <ul>
            <li><code>@js('sweetalert.min.js')</code> - Externe JavaScript-Bibliothek</li>
            <li><code>@js ... @endjs</code> - Inline JavaScript-Code</li>
        </ul>
    </div>
</div>
@endsection