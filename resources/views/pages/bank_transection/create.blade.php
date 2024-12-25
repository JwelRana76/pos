<x-admin title="Bank Transection Create">
    <x-page-header head="Bank Transection Create" />
    <div class="card p-3">
      <x-form method="post" id="transectionForm" action="{{ route('bank_transection.store') }}">
      <div class="row">
        <div class="col-md-6">
          <x-input id="date" type="date" value="{{ date('Y-m-d') }}" required/>
        </div>
        @if (count(accounts()) > 1)
        <div class="col-md-6 d-none">
          <label for="account">Account</label>
          <select name="account" id="account" class="form-control selectpicker" title="Select Account" data-live-search="true" >
            @foreach (accounts() as $key=>$item)
                <option value="{{ $item->id }}"  {{ $item->is_default == true ? 'selected':'' }}>{{ $item->name }} [{{ $item->account_no }}]</option>
            @endforeach
          </select>
        </div>
        @endif
        @if (count(banks()) > 0)
        <div class="col-md-6 mb-3">
          <label for="bank_id">Bank</label>
          <select name="bank" id="bank_id" class="form-control selectpicker" title="Select Bank" data-live-search="true" >
            @foreach (banks() as $key=>$item)
                <option value="{{ $item->id }}" {{ $item->is_default == true ? 'selected':'' }}>{{ $item->bank_name }} [{{ $item->account_no }}]</option>
            @endforeach
          </select>
        </div>
        @endif
        <div class="col-md-6">
            <label for="type">Loan Type</label>
            <select name="type" id="transection_type" class="form-control selectpicker" title="Select Transection Type">
                <option value="1">Deposit</option>
                <option value="0">Withdraw</option>
            </select>
        </div>
        <div class="col-md-6">
          <x-input id="amount" required />
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
      $('#transection_type').change(function () { 
        var bank = $('#transectionForm #bank_id').val();
        if(bank == null){
          alert('Please Select bank');
        }
        $('#transectionForm #amount').val(null);

      });
      
    const banks = @json($banks);
    const accounts = @json($accounts);
      $('#transectionForm #amount').on('input', function () {
        var bank = $('#transectionForm #bank_id').val();
        var account = $('#account').val();
        var transection_type = $('#transection_type').val();
        if(transection_type == 0){
          var filteredBank = banks.find(item => item.id == bank);
          var balance = parseFloat(filteredBank.balance);
          if(parseFloat($(this).val()) > balance){
            alert(`You can't withdraw more than ${balance}`);
            $(this).val(balance);
          }
          
        }else{
          var filteredBank = accounts.find(item => item.id == account);
          var balance = parseFloat(filteredBank.balance);
          if(parseFloat($(this).val()) > balance){
            alert(`You can't Deposit more than ${balance}`);
            $(this).val(balance);
          }
        }
      });
    });

  </script>
@endpush
</x-admin>

