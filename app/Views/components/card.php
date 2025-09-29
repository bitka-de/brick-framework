{{-- Card Komponente --}}
<div class="card {{ $class ?? '' }}" {{ $attributes ?? '' }}>
    @if($image ?? false)
        <img src="{{ $image }}" class="card-img-top" alt="{{ $imageAlt ?? 'Card Image' }}">
    @endif
    
    @if($header ?? false)
        <div class="card-header {{ $headerClass ?? '' }}">
            {{ $header }}
        </div>
    @endif
    
    <div class="card-body {{ $bodyClass ?? '' }}">
        @if($title ?? false)
            <h5 class="card-title">{{ $title }}</h5>
        @endif
        
        @if($subtitle ?? false)
            <h6 class="card-subtitle mb-2 text-muted">{{ $subtitle }}</h6>
        @endif
        
        @if($text ?? false)
            <p class="card-text">{{ $text }}</p>
        @endif
        
        {{ $slot ?? '' }}
        
        @if($actions ?? false)
            <div class="card-actions mt-3">
                {{ $actions }}
            </div>
        @endif
    </div>
    
    @if($footer ?? false)
        <div class="card-footer {{ $footerClass ?? '' }}">
            {{ $footer }}
        </div>
    @endif
</div>