<div class="modal-body">
    <form action="{{ route('organization.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">

                @if (session('success'))

                    <div class="alert alert-success alert-dismissible fade show" role="alert">

                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                @endif
                
                <form action="{{ route('organization.store') }}" method="POST" id="orgForm" autocomplete="off">
                    @csrf

                    <div class="row">
                        
                        <div class="col-lg-6 mb-3">

                            <label for="organization_name" class="form-label">{{ __('Organization Name') }}</label>
                            <input type="text" class="form-control required" id="organization_name" name="organization_name" tabindex="1"
                            value="{{ old('organization_name') }}" placeholder="Enter Organization Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                          <label for="organization_size" class="form-label">Organization Size</label>
                          <select name="organization_size" id="organization_size" class="form-select form-control required">
                            <option value="" selected disabled>Select Organization Size</option>

                            <option value="1-50" {{ old('organization_size') == '1-50' ? 'selected' : '' }}>1 - 50</option>

                            <option value="50-100" {{ old('organization_size') == '50-100' ? 'selected' : '' }}>50 - 100</option>

                            <option value="100-200" {{ old('organization_size') == '100-200' ? 'selected' : '' }}>100 - 200</option>

                            <option value="200+" {{ old('organization_size') == '200+' ? 'selected' : '' }}>200+</option>

                          </select>

                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="f_name" class="form-label">{{ __('First Name') }}</label>

                            <input type="text" class="form-control required" id="f_name" name="f_name" tabindex="1" value="{{ old('f_name') }}" placeholder="Enter First Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="l_name" class="form-label">{{ __('Last Name') }}</label>

                            <input type="text" class="form-control required" id="l_name" name="l_name" tabindex="1" value="{{ old('l_name') }}" placeholder="Enter First Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control required" id="email" name="email" tabindex="1"
                            value="{{ old('email') }}" placeholder="Enter email" autocomplete="off">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="phone" class="form-label">{{ __('Contact Number') }}</label>
                            <input type="number" class="form-control required" id="phone" name="phone" tabindex="1" value="{{ old('phone') }}" placeholder="Enter contact" maxlength="10">
                        </div>

                        <div class="col-lg-6">

                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password"
                                placeholder="******" tabindex="2" required>

                                <span class="input-group-text" id="toggle-password" style="cursor:pointer;">
                                    <i class="fa-solid fa-eye-slash"></i>
                                </span>

                            </div>

                        </div>

                        <!-- Confirm Password -->

                        <div class="col-lg-6 mb-3">

                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>

                            <input type="password" class="form-control required" id="password_confirmation"

                                   name="password_confirmation" tabindex="1" placeholder="******" autocomplete="off">

                        </div>

                    </div>

                    <div class="submit-section mb-3">
                        <x-form.button class="btn btn-primary submit-btn">{{ __('Add') }}</x-form.button>
                    </div>

                </form>

        </div>
    </form>
</div>
