<div class="input-group mb-3 mr-sm- {{ $class ?? '' }}">
    @if ($type != 'hidden')
    <div class="input-group-prepend" style="width: 40%">
        <div class="input-group-text"  style="width: 100%">{{ ucwords(str_replace('_', ' ', $id)) }}</div>
    </div>
    @endif
    <input 
        value="{{ $attributes['value'] ?? old($id) }}" 
        id="{{ $id }}" 
        class="form-control" 
        type="{{ $type }}"
        name="{{ $name == null ? $id:$name }}"
        {{ $attributes }}
    />
    @error($id)
        <strong class="text-danger">{{ $message }}</strong>
    @enderror
  </div>