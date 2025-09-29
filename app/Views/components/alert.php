{{-- Alert Komponente --}}
<div class="alert alert-{{ $type ?? 'info' }} {{ $dismissible ?? false ? 'alert-dismissible fade show' : '' }}" role="alert">
    @if($icon ?? true)
        @if($type === 'success')
            <i class="bi bi-check-circle-fill me-2"></i>
        @elseif($type === 'danger')
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
        @elseif($type === 'warning')
            <i class="bi bi-exclamation-circle-fill me-2"></i>
        @else
            <i class="bi bi-info-circle-fill me-2"></i>
        @endif
    @endif
    
    @if($title ?? false)
        <h6 class="alert-heading">{{ $title }}</h6>
    @endif
    
    {{ $message ?? $slot ?? 'Alert-Nachricht' }}
    
    @if($dismissible ?? false)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Schließen"></button>
    @endif
</div>