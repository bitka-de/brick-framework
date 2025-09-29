@extends('app')

@section('title', 'CSS Test')

@css('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css')

@section('content')
<div class="container">
    <h1 class="animate__animated animate__fadeInDown">CSS Test</h1>
    <p class="lead">Diese Seite testet eine einfache externe CSS-Direktive.</p>
    <div class="alert alert-success">
        <strong>Test erfolgreich!</strong> Die CSS-Direktive @css('animate.css') wurde geladen.
    </div>
</div>
@endsection