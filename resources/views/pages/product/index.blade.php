<x-admin title="Product">
    <x-page-header head="Product" />
    <a href="{{route('product.create')}}" class="btn btn-sm btn-primary m-2">
        <i class="fas fa-fw fa-plus"></i> Add Product
    </a>
    <button class="btn btn-sm btn-primary my-3" id="import_button" ><i class="fas fa-fw fa-upload"></i> Import</button>
    <div id="import_section" class="d-none mb-3">
        <div class="row">
            <div class="col-md-4 card p-3">
                <p>Excel file will be desing this partern. name,code,category,brand,size,unit,cost,price.</p>
                <x-form action="{{ route('product.import') }}" method="post">
                @csrf
                <x-input id="product_file" type="file" required />
                <button class="btn btn-primary" type="submit">Submit</button>
                </x-form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <x-data-table dataUrl="/product" id="products" :columns="$columns" />
        </div>
    </div>
    @push('js')
        <script>
            $(document).ready(function() {
                $('#import_button').on('click', function() {
                    if ($('#import_section').hasClass('d-none')) {
                    $('#import_section').removeClass('d-none').hide().slideDown('slow');
                } else {
                    $('#import_section').slideUp('slow', function() {
                        $(this).addClass('d-none');
                    });
                }
                });
            });
        </script>
    @endpush
</x-admin>
