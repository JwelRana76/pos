<x-admin title="Size">
    <x-page-header head="Size" />
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <x-form method="post" action="{{ route('size.store') }}">
                    <x-input id="id" type="hidden" value="{{ $size->id ?? null }}" />
                    <x-input id="name" label="নাম" value="{{ $size->name ?? null }}" />
                    <label for="code">Code</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="code" id="code" value="{{ $size->code ?? old('code') }}" >
                        <div class="input-group-append">
                            <input type="button" class="input-group-text" id="generate_code" id="basic-addon2" value="Generat" >
                        </div>
                    </div>
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-9">
            <x-data-table dataUrl="/setting/size" id="sizes" :columns="$columns" />
        </div>
    </div>
    @push('js')
        <script>
            $('#generate_code').on('click', function () {
                var min = 100;
                var max = 9999;
                var randomCode = Math.floor(Math.random() * (max - min + 1)) + min;
                $('input[name="code"]').val(randomCode);
            });
        </script>
    @endpush
</x-admin>