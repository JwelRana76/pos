<x-admin title="Advance Salary Payment">
  <x-page-header head="Advance Salary Payment" />
  <div class="card p-3">
    <x-form method="post" id="advancce_salary" action="{{ route('advance-salary.store') }}">
    <div class="row">
      <div class="col-md-6">
        <x-input id="month" type="month" value="{{ date('Y-m') }}" min="{{ date('Y-m') }}" required/>
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
        <x-input id="amount" required />
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
        const salaries = @json($advance_salarys);
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
          $.each(salaries, function (index, value) { 
             if((value.month == month) && (value.employee_id == employee_id)){
              $('#submit').addClass('d-none');
             }else{
              $('#submit').removeClass('d-none');
             }
          });
        }
        $('#advancce_salary').on('keydown', function(event) {
          var month = $('#month').val();
          var employee_id = $('select[name="employees"]').val();
          $.each(salaries, function (index, value) { 
             if((value.month == month) && (value.employee_id == employee_id)){
              if (event.key === "Enter") {
                event.preventDefault();
              }
             }
          });
        });
        $('#month').on('input', function () {
          checkMonth();
        });
        $('#employees').on('change', function () {
          checkMonth();
        });
      });
  </script>
@endpush
</x-admin>

