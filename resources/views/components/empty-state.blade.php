<div class="empty-state">
    <div class="empty-state-mark">{{ $mark ?? 'SYSTEX' }}</div>
    <strong>{{ $title }}</strong>
    <p>{{ $description }}</p>

    @isset($href)
        @if($href)
            <a href="{{ $href }}" class="btn-secondary">{{ $action ?? 'Cadastrar' }}</a>
        @endif
    @endisset
</div>
