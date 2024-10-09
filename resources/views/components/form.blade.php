<form method="{{ strtolower($method) == 'get' ? 'GET' : 'POST' }}" action="{{ $action }}" id="{{ $id }}"
    {{ $attributes }} autocomplete="off" enctype="multipart/form-data">
    @csrf

    @unless ($method == 'GET')
        @switch($method)
            @case('POST')
                @method('POST')
            @break

            @case('DELETE')
                @method('DELETE')
            @break

            @case('PUT')
                @method('PUT')
            @break

            @default
        @endswitch
    @endunless

    {{ $slot }}

    @slot('submit_button')
    @endslot
</form>
