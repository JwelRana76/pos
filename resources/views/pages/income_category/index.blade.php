<x-admin title="Income Category">
    <x-page-header head="Income Category" />
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <x-form method="post" action="{{ route('income-category.store') }}">
                    <x-input id="id" type="hidden" value="{{ $category->id ?? null }}" />
                    <x-input id="name" label="নাম" value="{{ $category->name ?? null }}" />
                    <label for="code">Code</label>
                    <div class="input-group flex-nowrap">
                        <input type="text" required name="code" id="category_code" value="{{ $category->code ?? old('code') }}" 
                            class="form-control" placeholder="Category Code">
                        <span style="cursor: pointer; border-radius: 0;" class="input-group-text" id="category_code_gen">
                            <i class="fa fa-barcode"></i>
                        </span>
                    </div>
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-9">
            <x-data-table dataUrl="/income-category" id="incomeCategories" :columns="$columns" />
        </div>
    </div>
    @push('js')
        <script>
            $('#category_code_gen').on('click', function () {
                const inputEl = $(this).prev();
                const random = (Math.random() + 1).toString(10).substring(14);
                inputEl.val(random);
            });
        </script>
    @endpush
</x-admin>
