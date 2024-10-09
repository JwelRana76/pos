<div class="form-group mb-3 {{ $class }}">
    <label for="{{ $id }}">{{ ucwords(str_replace('_', ' ', $id)) }}
        <strong> {{ $attributes['required'] ? '*' : '' }}
        </strong>
    </label>
    <textarea id="{{ $id }}" class="form-control" cols="10" rows="{{ $row ?? 3 }}" name="{{ $id }}"
        {{ $attributes }}>
    </textarea>
    @error($id)
        <strong class="text-danger">{{ $message }}</strong>
    @enderror
</div>
