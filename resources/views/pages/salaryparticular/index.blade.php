<x-admin title="Salary Particular">
    <x-page-header head="Salary Particular" />
    <div class="row">
        <div class="col-md-3">
            <div class="card p-3">
                <x-form method="post" action="{{ route('salary-particular.store') }}">
                    <x-input id="id" type="hidden" value="{{ $salaryparticular->id ?? null }}" />
                    <x-input id="name" value="{{ $salaryparticular->name ?? null }}" />
                    <div class="form-group form-check">
                        <input type="hidden" name="is_provident" value="0">
                        <input type="checkbox" class="form-check-input" id="is_provident" 
                        @isset($salaryparticular)
                        {{ $salaryparticular->is_provident == true ? 'checked':'' }}
                        @endisset 
                        name="is_provident" value="1">
                        <label class="form-check-label" for="is_provident">Is Provident</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="hidden" name="is_constant" value="0">
                        <input type="checkbox" class="form-check-input" id="is_constant" 
                        @isset($salaryparticular)
                        {{ $salaryparticular->is_constant == true ? 'checked':'' }}
                        @endisset 
                        name="is_constant"  value="1">
                        <label class="form-check-label" for="is_constant">Is Constant</label>
                    </div>
                    <x-button value="Save" />
                </x-form>
            </div>
        </div>
        <div class="col-md-9">
            <x-data-table dataUrl="/payrole/salary-particular" id="salaryparticulars" :columns="$columns" />
        </div>
    </div>
</x-admin>
