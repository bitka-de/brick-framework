@extends('app')

@section('title', 'CSS Inline Test')

@css
.custom-box {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 10px;
    margin: 1rem 0;
    text-align: center;
}
@endcss

@section('content')
<div class="container">
    <h1>CSS Inline Test</h1>
    <p class="lead">Diese Seite testet Inline-CSS-Direktiven.</p>
    
    <div class="custom-box">
        <h3>Custom Styled Box</h3>
        <p>Diese Box verwendet Inline CSS aus der @css...@endcss Direktive.</p>
    </div>
    
    <div class="alert alert-info">
        <strong>CSS Direktive funktioniert!</strong> Die Inline-Styles wurden in den Header eingefügt.
    </div>
</div>
@endsection