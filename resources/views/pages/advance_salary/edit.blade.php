<x-admin title="Advance Salary Edit">
  <x-page-header head="Advance Salary Edit" />
  <div class="card p-3">
    <x-form method="post" action="{{ route('advance-salary.update',$salary->id) }}">
    <div class="row">
      <div class="col-md-6">
        <x-input id="month" type="month" value="{{ $salary->month }}" required/>
      </div>
      <div class="col-md-6">
        <x-select id="employees" required selectedId="{{ $salary->employee_id  }}" :options="$employees"/>
      </div>
      <div class="col-md-6">
        <label for="payment_type">Payment Type *</label>
        <select name="payment_type" id="payment_type" required class="form-control selectpicker" live-data-serach="true" title="select payment type">
          <option value="0" {{ $salary->payment_type == 0 ?'selected':'' }}>Bank</option>
          <option value="1" {{ $salary->payment_type == 1 ?'selected':'' }}>Cash</option>
        </select>
      </div>
      <div class="col-md-6 d-none" id="account_section">
        <x-select id="account" selectedId="{{ $salary->account_id }}"
        :options="$accounts" />
      </div>
      <div class="col-md-6 d-none" id="bank_section">
        <x-select id="bank" selectedId="{{ $salary->bank_id }}" :options="$banks" />
      </div>
      <div class="col-md-6">
        <x-input id="amount" value="{{ $salary->amount }}" required />
      </div>
      <div class="col-md-12">
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
      });
  </script>
@endpush
</x-admin>

