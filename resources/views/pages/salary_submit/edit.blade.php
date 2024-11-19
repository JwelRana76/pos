<x-admin title="Salary Submition">
    <x-page-header head="Salary Submition" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('salary-submit.update',$salary->id) }}">
      <div class="row">
        <div class="col-md-6">
          <x-input id="date" type="month" value="{{ $salary->month }}" max="{{ date('Y-m', strtotime('-1 month')) }}" required/>
        </div>
        <div class="col-md-6">
          <x-select id="employees" selectedId="{{ $salary->employee->id }}" :options="$employees"/>
        </div>
        <div class="col-md-12">
          <hr>
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
                @php
                  $amount = 0;
                  $crop = 0;
                    foreach ($salary->details as $value) {
                      if($value->salary_particular_id == $item->id){
                        $amount = $value->amount;
                      }
                      if($value->salary_particular_id == 0){
                        $crop = $value->amount;
                      }
                    }
                @endphp
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->name }}</td>
                        <td><input value="{{ $amount }}" {{ $item->is_constant == true ? 'readonly':'' }} type="text" class="form-control amount" name="amount[{{ $item->id }}]"></td>
                    </tr>
                @endforeach
                <tr>
                  <td>{{ ++$key }}</td>
                  <td>Salary Crop</td>
                  <input type="hidden" id="provident_fund" value="{{ $salary->provident_fund }}" name="provident_fund">
                  <input type="hidden" id="total_pryable" value="{{ $salary->total_payable }}" name="total_pryable">
                  <td><input type="text" value="{{ $crop }}" class="form-control crop" name="amount[crop]"></td>
                </tr>
                <tr>
                  <td>{{ ++$key }}</td>
                  <td>Note</td>
                  <td><textarea row="3" cols="10" class="form-control note" name="note">{{ $salary->note }}</textarea></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" class="text-right">Total</th>
                    <th id="total_salary"></th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">Provident Fund</th>
                    <th id="prodvident_fund">{{ $salary->provident_fund }}</th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">Salary Crop</th>
                    <th id="salary_crop"></th>
                </tr>
                <tr>
                    <th colspan="2" class="text-right">Total Payable</th>
                    <th id="total_payable"></th>
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
      $(document).ready(function () {
        total_calculation();
      });
      function total_calculation(){
        var total_salary = 0;
        $('#salary-product-table tr').each(function() {
            var row = $(this);
            var subtotal = parseFloat(row.find('.amount').val() || 0);
            total_salary += subtotal;
        });
        var crop = parseFloat($('input[name="amount[crop]"]').val()) || 0;
        total_salary -= crop;
        $('#total_salary').text(total_salary.toFixed(2));
        $('#salary_crop').text(crop.toFixed(2));
        var provident_fund = parseFloat($('#provident_fund').val()) || 0;
        var total_payable = total_salary - provident_fund;
        $('#total_payable').text(total_payable.toFixed(2));
        $('#total_pryable').val(total_payable);
      }
      $('.amount').on('input', function () {
          total_calculation();
      });
      $('.crop').on('input', function () {
          total_calculation();
      });
      $('#employees').on('change', function () {
        salarydetails($(this).val());
      });
      function salarydetails(employee_id) {
        $.get("/payrole/salary-assign/details/" + employee_id, function (data) {
            if (data && data.length > 0) {
                $('input[name^="amount["]').val('');
                $.each(data, function (index, value) {
                    $('input[name="amount[' + value.salary_particular_id + ']"]').val(value.amount);
                    if(value.is_provident == true){
                      $('#prodvident_fund').text(value.amount.toFixed(2));
                      $('#provident_fund').val(value.amount);
                    }
                });
              total_calculation();
            } else {
                // Clear all input fields if no data is found
                $('input[name^="amount["]').val('');
              total_calculation();
            }
        });
      }
  </script>
@endpush
</x-admin>

