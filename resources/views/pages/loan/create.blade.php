<x-admin title="Loan Create">
    <x-page-header head="Loan Create" />
    <div class="card p-3">
      <x-form method="post" action="{{ route('loan.store') }}">
      <div class="row">
        <div class="col-md-6">
          <x-input id="date" type="date" value="{{ date('Y-m-d') }}" required/>
        </div>
        <div class="col-md-6">
          <x-input id="name" required/>
        </div>
        <div class="col-md-6">
          <x-input id="contact" required/>
        </div>
        <div class="col-md-6">
          <x-select id="account" :options="accounts()" required />
        </div>
        <div class="col-md-6">
          <x-input id="amount" required />
        </div>
        <div class="col-md-6">
          <x-input id="interest" placeholder="enter interest %" required/>
        </div>
        <div class="col-md-12">
          <x-text-area id='note' name="note" value="{{ $invest->note ?? null  }}" />
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

