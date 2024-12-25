<x-admin title="Salary Payment">
  <x-page-header head="Salary Payment" />
  <div class="card p-3">
    <x-form method="post" id="salary_payment" action="{{ route('salary-payment.store') }}">
    <div class="row">
      <div class="col-md-6">
        <x-input id="date" type="date" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required/>
      </div>
      <div class="col-md-6">
        <x-input id="month" type="month" value="{{ date('Y-m', strtotime('-1 month')) }}" max="{{ date('Y-m', strtotime('-1 month')) }}" required/>
      </div>
      <div class="col-md-6">
        <x-select id="employees" required :options="$employees"/>
      </div>
      <div class="col-md-6">
        <label for="payment_type">Payment Type *</label>
        <select name="payment_type" id="payment_type" required class="form-control selectpicker" live-data-serach="true" title="select payment type">
          <option value="0">Bank</option>
          <option value="1" selected>Cash</option>
        </select>
      </div>
      <div class="col-md-6 d-none" id="account_section">
        <x-select id="account" selectedId="{{ count($accounts) == 1 ? $accounts[0]->id:'' }}"
        :options="$accounts" />
      </div>
      <div class="col-md-6 d-none" id="bank_section">
        <x-select id="bank" selectedId="{{ count($banks) == 1 ? $banks[0]->id:'' }}" :options="$banks" />
      </div>
      <div class="col-md-6">
        <x-input id="monthly_salary" readonly />
        <x-input id="monthly_salary_id" type="hidden" />
      </div>
      <div class="col-md-6">
        <x-input id="advance_paid" readonly />
      </div>
      <div class="col-md-6">
        <x-input id="due_salary" readonly />
      </div>
      <div class="col-md-6">
        <x-input id="total_salary" readonly />
      </div>
      <div class="col-md-6">
        <x-input id="paid" required />
      </div>
      <div class="col-md-12" id="submit">
        <x-button value="Save" />
      </div>
      
    </div>
    </x-form>
  </div>


@push('js')
  <script>
      $(document).ready(function () {
        $('#payment_type').change(function () { 
          var type = $(this).val();
          if(type == 0){
            $('#account_section').addClass('d-none');
            $('#bank_section').removeClass('d-none');
          }else{
            $('#bank_section').addClass('d-none');
            $('#account_section').removeClass('d-none');
          }
        });

        function checkMonth(){
          var month = $('#month').val();
          var employee_id = $('select[name="employees"]').val();
          $.get("/payrole/salary-payment/salary_details/" + month + '/' + employee_id, 
            function(data) {
              $('#monthly_salary_id').val(data.monthly_salary_id);
              $('#monthly_salary').val(data.monthly_salary);
              $('#advance_paid').val(data.advance_salary);
              $('#due_salary').val(data.due);
              calculation();
            }
          );
        }
        $('#month').on('input', function () {
          checkMonth();
        });
        
        $('#employees').on('change', function () {
          checkMonth();
        });
        function calculation(){
          var monthly_salary = parseFloat($('#monthly_salary').val());
          var advance_paid = parseFloat($('#advance_paid').val());
          var due_salary = parseFloat($('#due_salary').val());
          $('#total_salary').val(monthly_salary - advance_paid + due_salary);
        }
        $('#salary_payment #paid').on('input', function () {
          var salary = parseFloat($('#total_salary').val());
          if(parseFloat($(this).val()) > salary){
            alert(`You can not pay more than ${salary}`);
            $(this).val(salary);
          }
        });
      });
  </script>
@endpush
</x-admin>

