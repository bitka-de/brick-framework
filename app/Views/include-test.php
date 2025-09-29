@extends('app')

@section('title', 'Include Test')

@section('content')
<div class="container">
    <h1>Include Directive Test</h1>
    
    <h3>Einfache Includes:</h3>
    @include('components.card')
    
    <h3>Include mit Parametern:</h3>
    @include('components.card', ['title' => 'Test Card', 'text' => 'Dies ist ein Test der erweiterten Include-Funktionalität.'])
    
    <h3>Alert Include:</h3>
    @include('components.alert', ['type' => 'success', 'title' => 'Erfolg!', 'message' => 'Das Include-System funktioniert!'])
</div>
@endsection