<x-admin title="Salary Assign">
      @php
        $currentUrl = url()->current();

        // Parse the URL to get the path
        $urlPath = parse_url($currentUrl, PHP_URL_PATH);

        // Split the path by '/' and get the last segment
        $segments = explode('/', $urlPath);
        $employeeId = end($segments);
      @endphp
    <x-page-header head="Salary Assign" />
    <div class="card p-3 row col-md-6">
        <x-form method="post" action="{{ route('salary-assign.store') }}">
        <div class="row">
          <div class="col-md-6">
            <x-input id="date" type="date" value="{{ date('Y-m-d') }}" required />
          </div>
          <div class="col-md-6">
            <x-select id="employees" selectedId="{{ $employeeId }}" name="employee_id" :options="$employees" />
          </div>
          @php
            $particulars = App\Models\SalaryParticular::active();
          @endphp
          <table class="table mt-3" >
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Particular Name</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody id="salary-product-table">
                @foreach ($particulars as $key=>$item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->name }}</td>
                        <td><input type="text" class="form-control amount" name="amount[{{ $item->id }}]"></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th id="total_salary"></th>
                </tr>
            </tfoot>
          </table>
          <div class="col-md-12">
            <x-button value="Save" />
          </div>
        </div>
        </x-form>
      </div>
      @push('js')
      <script>
        function total_calculation(){
            var total_salary = 0;
            $('#salary-product-table tr').each(function() {
                var row = $(this);
                var subtotal = parseFloat(row.find('.amount').val() || 0);
                total_salary += subtotal;
            });
            $('#total_salary').text(total_salary.toFixed(2));
        }
        $('.amount').on('input', function () {
            total_calculation();
        });
        salarydetails(@json($employeeId));
        $('#employees').on('change', function () {
          salarydetails($(this).val());
        });
        function salarydetails(employee_id) {
          $.get("/payrole/salary-assign/details/" + employee_id, function (data) {
              if (data && data.length > 0) {
                  $('input[name^="amount["]').val('');
                  $.each(data, function (index, value) {
                      $('input[name="amount[' + value.salary_particular_id + ']"]').val(value.amount);
                  });
              } else {
                  // Clear all input fields if no data is found
                  $('input[name^="amount["]').val('');
              }
              total_calculation();
          });
        }

      </script>
      @endpush
</x-admin>
