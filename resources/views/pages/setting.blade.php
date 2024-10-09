<x-admin title="Site Seeting">
    <x-page-header head="Site Setting" />
    <div class="row">
        <div class="col-md-6">
            <x-card header="Site Setting">
                <x-form method="post" action="{{ route('site_setting.update', $setting->id) }}">
                    <x-input id="name" value="{{ $setting->name }}" />
                    <x-input id="name_short" value="{{ $setting->name_short }}" />
                    <x-input id="address" value="{{ $setting->address }}" />
                    <x-input id="contact" value="{{ $setting->contact }}" />
                    <x-input id="email" value="{{ $setting->email }}" />
                    <x-input type="file" id="logo" />
                    @if ($setting->logo)
                        <div class="row">
                            <img src="/upload/{{ $setting->logo }}" alt="Logo" width="50px" height="auto">
                        </div>
                    @endif
                    <x-button value="Save" />
                </x-form>
            </x-card>
        </div>
    </div>
</x-admin>
