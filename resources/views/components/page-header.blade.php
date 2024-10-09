<div class="row page-header">
    <div class="col-md-6">
        <h3>{{ $head }}</h3>
    </div>
    <div class="col-md-6">
        <div class="float-right right-content">
            <ul class="nav">
                <li class="mr-2"><a href="/">Home </a></li>
                @if ($second)
                    <li class="mr-2"><a href="{{ route($second . '.index') }}">/ {{ $second }}</a></li>
                @endif

                <li> / {{ $head }}</li>
            </ul>
        </div>
    </div>
</div>
