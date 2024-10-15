<div class="card">
    <div class="card-header">
        <h5 style="margin: 0">{{ $header }}
            @if ($links)
                <a href="{{ $links }}" class="btn btn-sm btn-primary float-right">{{ $title }}</a>
            @endif
        </h5>
    </div>
    <div class="card-body py-3" style="padding: 2px">
        {{ $slot }}
    </div>
</div>