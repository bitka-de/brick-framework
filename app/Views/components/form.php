{{-- Form Komponente --}}
<form method="{{ $method ?? 'POST' }}" action="{{ $action ?? '' }}" {{ $attributes ?? '' }}>
    @if(($method ?? 'POST') !== 'GET')
        {{-- CSRF Protection würde hier eingefügt werden --}}
        <input type="hidden" name="_token" value="{{ $csrfToken ?? '' }}">
    @endif
    
    @if(($method ?? 'POST') !== 'GET' && ($method ?? 'POST') !== 'POST')
        <input type="hidden" name="_method" value="{{ $method }}">
    @endif
    
    {{ $slot ?? '' }}
</form>