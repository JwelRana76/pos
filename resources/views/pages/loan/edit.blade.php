<x-admin title="Loan Create">
    <x-page-header head="Loan Create" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('loan.update',$loan->id) }}">
      <div class="row">
        <div class="col-md-6">
          <x-input id="date" type="date" value="{{ $loan->created_at->format('Y-m-d') }}" required/>
        </div>
        <div class="col-md-6">
          <x-input id="name" value="{{ $loan->name }}" required/>
        </div>
        <div class="col-md-6">
          <x-input id="contact" value="{{ $loan->contact }}" required/>
        </div>
        <div class="col-md-6">
          <x-select id="account" selectedId="{{ $loan->account_id }}" :options="accounts()" required />
        </div>
        <div class="col-md-6">
          <x-input id="amount" value="{{ $loan->amount }}" required />
        </div>
        <div class="col-md-6">
            <label for="loan_type">Loan Type</label>
            <select name="loan_type" id="loan_type" class="form-control selectpicker" title="Select Loan Type">
                <option value="1" {{ $loan->loan_type == 1?'selected':'' }}>Give (-)</option>
                <option value="0" {{ $loan->loan_type == 0?'selected':'' }}>Take (+)</option>
            </select>
        </div>
        <div class="col-md-6 d-none">
          <x-input id="interest" value="{{ $loan->interest }}" placeholder="enter interest %" />
        </div>
        <div class="col-md-12">
          <x-text-area id='note' name="note" value="{{ $loan->note  }}" />
        </div>
        
        <div class="col-md-12">
          <x-button value="Save" />
        </div>
        
      </div>
      </x-form>
    </div>
 @php
        $accounts = App\Models\Account::select('name as name', 'id', 'account_no')
            ->get()
            ->append('balance');
    @endphp

@push('js')
  <script>
    const accounts = @json($accounts);
    
    $('#loan_form #amount').on('input', function () {
        var account = $('#account').val();
        var loan_type = $('#loan_type').val();
        if(loan_type == 1){
          var filteredBank = accounts.find(item => item.id == account);
          var balance = parseFloat(filteredBank.balance);
          if(parseFloat($(this).val()) > balance){
            alert(`You can't Give more than ${balance}`);
            $(this).val(balance);
          }
        }
      });
  </script>
@endpush
</x-admin>

