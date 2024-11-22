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
          <x-input id="interest" value="{{ $loan->interest }}" placeholder="enter interest %" required/>
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

@push('js')
  <script>
    
  </script>
@endpush
</x-admin>

