<div class="form-group mb-2">
    @if (!$attributes['hide-label'])
        <label class="form-label" for="{{ $id }}">
            {{ ucwords(str_replace('_', ' ', $id)) }}
            <strong>
                {{ $attributes['required'] ? '*' : '' }}
            </strong>
        </label>
    @endif

    @if ($attributes['has-modal'])
        <div class="input-group">
    @endif

    <select name="{{ $id }}" id="{{ $id }}" class="form-control selectpicker" data-live-search="true"
        title="Select {{ ucwords(str_replace('_', ' ', $id)) }}" {{ $attributes }}>
        @foreach ($options as $option)
            <option
                @php
$selected = $attributes['selectedId'] == (is_array($option) ? $option['id'] : $option->id);
                    
                    if(!is_array($option) && $option->is_default) {
                        $selected = true;
                    } @endphp
                {{ $selected ? 'selected' : '' }} value="{{ is_array($option) ? $option['id'] : $option->id }}">
                {{ is_array($option) ? $option['name'] : $option->name }}</option>
        @endforeach
    </select>

    @if ($attributes['has-modal'])
        <div class="input-group-append">
            <span class="input-group-text" data-toggle="modal" data-target="#{{ $attributes['modal-open-id'] }}"><i
                    class="fa fa-fw fa-plus"></i></span>
        </div>
</div>
@endif

@error($id)
    <strong class="text-danger">{{ $message }}</strong>
@enderror
</div>
