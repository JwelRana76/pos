<x-admin title="Bank Transection">
    <x-page-header head="Bank Transection" />
    <a href="{{route('bank_transection.create')}}" class="btn btn-sm btn-primary m-2">
        <i class="fas fa-fw fa-plus"></i> New Transection
    </a>
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/bank-transection" id="bank_transections" :columns="$columns" />
        </div>
    </div>
    @push('js')
        <script>
            
        </script>
    @endpush
</x-admin>
