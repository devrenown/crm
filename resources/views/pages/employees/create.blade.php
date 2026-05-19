<div class="modal-body">
    <form action="{{ route('employees.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('First Name') }}</x-form.label>
                    <x-form.input type="text" name="firstname" />
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
                    <x-form.label>{{ __('Last Name') }}</x-form.label>
                    <x-form.input type="text" name="lastname" />
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
                    <x-form.label>{{ __('Email') }}</x-form.label>
                    <x-form.input type="email" name="email" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Phone Number') }}</x-form.label>
                    <x-form.phone type="text" name="phone" maxlength="10" />
                </div>
            </div>
            <p class="text-muted mb-0 text-sm">
                Note: The password has been auto-generated. You may share it with the user or ask them to change it after first login.
            </p>
            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Password') }}
                    </x-form.label>
                    <x-form.input type="text" value="REMP1234" name="password" readonly />
                </x-form.input-block>
            </div>
            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Confirm Password') }}
                    </x-form.label>
                    <x-form.input type="text" value="REMP1234" name="password_confirmation" readonly />
                </x-form.input-block>
            </div>

            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Reporting Manager') }}
                    </x-form.label>
                    <select name="reporting_manager" id="reporting_manager" class="select">
                        @if (!empty($userList))
                            <option value="" disabled selected>Reporting Manger</option>
                            @foreach ($userList as $list)
                                <option value="{{ $list->id }}">{{ $list->fullname }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>

            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Sub Reporting Manager') }}
                    </x-form.label>
                    <select name="sub_reporting_manager" id="sub_reporting_manager" class="select">
                        @if (!empty($userList))
                            <option value="" disabled selected>Sub Reporting Manger</option>
                            @foreach ($userList as $list)
                                <option value="{{ $list->id }}">{{ $list->fullname }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>

            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Department') }}
                    </x-form.label>
                    <select name="department" id="department" class="select">
                        @if (!empty($departments))
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>

            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Designation') }}
                    </x-form.label>
                    <select name="designation" id="designation" class="select">
                        @if (!empty($designations))
                            @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>
            
            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Joining Date') }}</label>
                    <div class="cal-icon">
                        <input id="date_joined" name="date_joined" type="text"
                            class="form-control datepicker">
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <label class="col-form-label">{{ __('Shift') }}</label>
                <select name="shift" id="shift" class="select" required>
                  @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                  @endforeach
                </select>
            </div>

            <div class="col-sm-6">
                <label class="col-form-label">{{ __('Experience Level') }}</label>
                <select name="experience_level" id="experience_level" class="select" required>
                    <option value="" disabled selected>--Choose--</option>
                    <option value="experienced">Experienced</option>
                    <option value="fresher">Fresher</option>
                </select>
            </div>

            <div class="col-sm-6">
                <label class="col-form-label">{{ __('Status') }}</label>
                <select name="status" id="status" class="select" required>
                    <option value="2">Pending</option>
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                </select>
            </div>

            {{-- <div class="col-sm-6">
                <div class="status-toggle">
                    <label class="col-form-label">{{ __('Status') }}</label>
                    <x-form.input type="checkbox" id="add-emp-status" class="check" name="status" />
                    <label for="status" class="checktoggle">checkbox</label>
                </div>
            </div> --}}
        </div>
        <div class="submit-section mb-3">
            <x-form.button class="btn btn-primary submit-btn">{{ __('Submit') }}</x-form.button>
        </div>
    </form>
</div>

<!-- <script>
    $(document).on('click', '.checktoggle', function () {
        $('#add-emp-status').prop('checked', !$('#add-emp-status').prop('checked'));
    });
</script> -->
