<x-admin title="Expense Category">
    <x-page-header head="Expense Category" />
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <x-form method="post" action="{{ route('expense-category.store') }}">
                    <x-input id="id" type="hidden" value="{{ $category->id ?? null }}" />
                    <x-input id="name" label="নাম" value="{{ $category->name ?? null }}" />
                    <label for="code">Code</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="code" id="code" value="{{ $category->code ?? old('code') }}" >
                        <div class="input-group-append">
                            <input type="button" class="input-group-text" id="generate_code" id="basic-addon2" value="Generat" >
                        </div>
                    </div>
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-9">
            <x-data-table dataUrl="/expense-category" id="expenseCategories" :columns="$columns" />
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
