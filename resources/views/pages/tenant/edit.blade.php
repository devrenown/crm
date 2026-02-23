<div class="modal-body">
        
        <div class="row">
                
                <form action="{{ route('tenant.update') }}" method="POST" autocomplete="off">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                    <input type="hidden" name="user_id" value="{{ $tenant->adminUser->id }}">

                    <div class="row">
                        
                        <div class="col-lg-6 mb-3">

                            <label for="organization_name" class="form-label">{{ __('Organization Name') }}</label>
                            <input type="text" class="form-control required" id="organization_name" name="organization_name" tabindex="1"
                            value="{{ $tenant->name }}" placeholder="Enter Organization Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                          <label for="organization_size" class="form-label">Organization Size</label>
                          <select name="organization_size" id="organization_size" class="form-select form-control required">
                            <option value="" selected disabled>Select Organization Size</option>

                            <option value="1-50" {{ $tenant->size == '1-50' ? 'selected' : '' }}>1 - 50</option>

                            <option value="50-100" {{ $tenant->size == '50-100' ? 'selected' : '' }}>50 - 100</option>

                            <option value="100-200" {{ $tenant->size == '100-200' ? 'selected' : '' }}>100 - 200</option>

                            <option value="200+" {{ $tenant->size == '200+' ? 'selected' : '' }}>200+</option>

                          </select>

                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="f_name" class="form-label">{{ __('First Name') }}</label>

                            <input type="text" class="form-control required" id="f_name" name="f_name" tabindex="1" value="{{ @$tenant->adminUser->firstname }}" placeholder="Enter First Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="l_name" class="form-label">{{ __('Last Name') }}</label>

                            <input type="text" class="form-control required" id="l_name" name="l_name" tabindex="1" value="{{ @$tenant->adminUser->lastname }}" placeholder="Enter First Name">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control required" id="email" name="email" tabindex="1"
                            value="{{ @$tenant->adminUser->email }}" placeholder="Enter email" autocomplete="off">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="phone" class="form-label">{{ __('Contact Number') }}</label>
                            <input type="number" class="form-control required" id="phone" name="phone" tabindex="1" value="{{ @$tenant->adminUser->phone }}" placeholder="Enter contact" maxlength="10">
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select class="form-control form-select" name="status" id="status"> 
                                <option value="active" {{ $tenant->status === \App\Enums\TenantStatus::ACTIVE ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $tenant->status === \App\Enums\TenantStatus::INACTIVE ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ $tenant->status === \App\Enums\TenantStatus::SUSPENDED ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        {{-- <div class="col-lg-6">

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

                            <input type="password" class="form-control required" id="password_confirmation" name="password_confirmation" tabindex="1" placeholder="******" autocomplete="off">
                        </div> --}}

                    </div>

                    <div class="submit-section mb-3">
                        <x-form.button class="btn btn-primary submit-btn">{{ __('Update') }}</x-form.button>
                    </div>

                </form>

        </div>
</div>
