<div class="modal-body">
    <form action="{{ route('clients.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-block mb-3">
                            <x-form.label>{{ __('First Name') }}<span class="text-danger">*</span></x-form.label>
                            <x-form.input type="text" name="firstname" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-block mb-3">
                            <x-form.label>{{ __('Middle Name') }}</x-form.label>
                            <x-form.input type="text" name="middlename" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-block mb-3">
                            <x-form.label>{{ __('Last Name') }}<span class="text-danger">*</span></x-form.label>
                            <x-form.input type="text" name="lastname" required />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-block mb-3">
                            <x-form.label>{{ __('UserName') }}</x-form.label>
                            <x-form.input type="text" name="username" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-block mb-3">
                            <x-form.label>{{ __('Email') }}<span class="text-danger">*</span></x-form.label>
                            <x-form.input type="email" name="email" required/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-block mb-3">
                            <label>{{ __('Phone Number') }}</label>
                            <x-form.phone type="text" name="phone" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <x-form.input-block>
                            <x-form.label>
                                {{ __('Password') }}<span class="text-danger">*</span>
                            </x-form.label>
                            <x-form.input type="password" name="password" required/>
                        </x-form.input-block>
                    </div>
                    <div class="col-sm-6">
                        <x-form.input-block>
                            <x-form.label>
                                {{ __('Confirm Password') }}<span class="text-danger">*</span>
                            </x-form.label>
                            <x-form.input type="password" name="password_confirmation" required />
                        </x-form.input-block>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Address') }}</x-form.label>
                    <x-form.input type="text" name="address" />
                </div>
            </div>

        </div>
        <div class="row">
            <div class="input-block mb-3">
                <label class="col-form-label">{{ __('Avatar') }}</label>
                <x-form.input type="file" name="avatar" />
            </div>
            <div class="status-toggle">
                <x-form.input type="checkbox" id="status" class="check" name="status" />
                <label for="status" class="checktoggle">checkbox</label>
            </div>
        </div>
        <div class="submit-section">
            <x-form.button class="btn btn-primary submit-btn">{{ __('Submit') }}</x-form.button>
        </div>
    </form>
</div>
