<div class="modal-body">
    <form action="{{ route('employees.update', $employee->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <x-form.input-block>
                    <x-form.label>{{ __('First Name') }}</x-form.label>
                    <x-form.input type="text" name="firstname" value="{{ $employee->firstname }}" />
                </x-form.input-block>
            </div>
            <div class="col-md-6">
                <x-form.input-block>
                    <x-form.label>{{ __('Middle Name') }}</x-form.label>
                    <x-form.input type="text" name="middlename" value="{{ $employee->middlename }}" />
                </x-form.input-block>
            </div>
            <div class="col-md-6">
                <x-form.input-block>
                    <x-form.label>{{ __('Last Name') }}</x-form.label>
                    <x-form.input type="text" name="lastname" value="{{ $employee->lastname }}" />
                </x-form.input-block>
            </div>
            <div class="col-md-6">
                <x-form.input-block>
                    <x-form.label>{{ __('UserName') }}</x-form.label>
                    <x-form.input type="text" name="username" value="{{ $employee->username }}" autocomplete="off" />
                </x-form.input-block>
            </div>
            <div class="col-md-6">
                <x-form.input-block>
                    <x-form.label>{{ __('Email') }}</x-form.label>
                    <x-form.input type="email" name="email" value="{{ $employee->email }}" />
                </x-form.input-block>
            </div>
            <div class="col-md-6">
                <x-form.input-block>
                    <label>{{ __('Phone Number') }}</label>
                    <x-form.phone type="text" name="phone" value="{{ $employee->phone }}" />
                </x-form.input-block>
            </div>
            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Password') }}
                    </x-form.label>
                    <x-form.input type="password" name="password" autocomplete="off" />
                </x-form.input-block>
            </div>
            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Confirm Password') }}
                    </x-form.label>
                    <x-form.input type="password" name="password_confirmation" />
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
                                <option value="{{ $list->id }}" {{ $list->id == $employee->reporting_manager ? 'selected' : '' }}>{{ $list->fullname }}</option>
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
                                <option value="{{ $list->id }}" {{ $list->id == $employee->sub_reporting_manager ? 'selected' : '' }}>{{ $list->fullname }}</option>
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
                                <option {{ (!empty($employee->employeeDetail) && ($employee->employeeDetail->department_id == $department->id)) ? 'selected': '' }} value="{{ $department->id }}">{{ $department->name }}</option>
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
                                <option {{ (!empty($employee->employeeDetail) && ($employee->employeeDetail->designation_id == $designation->id)) ? 'selected': '' }} value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>

            <div class="col-sm-6">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Joining Date') }}</label>
                    <div class="cal-icon">
                        <input id="date_joined" name="date_joined" value="{{ $employee->employeeDetail?->date_joined ?? '' }}" type="text"
                            class="form-control datepicker">
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <label class="col-form-label">{{ __('Shift') }}</label>
                
                <select name="shift" id="shift" class="select" required>
                  @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ @$employee->shift->shift_id == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                  @endforeach
                </select>
            </div>

            <div class="col-sm-6">
                <label class="col-form-label">{{ __('Experience Level') }}</label>
                <select name="experience_level" id="experience_level" class="select" required>
                    <option value="" disabled selected>--Choose--</option>
                    <option value="experienced" {{ optional($employee->onboarding)->type == 'experienced' ? 'selected' : '' }}>Experienced</option>
                    <option value="fresher" {{ optional($employee->onboarding)->type == 'fresher' ? 'selected' : '' }}>Fresher</option>
                </select>
            </div>

            <div class="col-sm-6">
                <label class="col-form-label">{{ __('Status') }}</label>
                <select name="status" id="status" class="select" required>
                    <option value="2" {{ $employee->is_active == 2 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $employee->is_active == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ $employee->is_active == 0 ? 'selected' : '' }}>Deactive</option>
                </select>
            </div>

            {{-- <div class="col-sm-6">
                <div class="status-toggle">
                    <x-form.label>{{ __('Status') }}</x-form.label>
                    <input type="checkbox" id="edit-emp-status" class="form-control check" name="status" {{ $employee->is_active == 1 ? 'checked' : '' }} />
                    <label for="status" class="checktoggle">checkbox</label>
                </div>
            </div> --}}
        </div>
        <div class="submit-section mb-3">
            <x-form.button type="submit" class="btn btn-primary submit-btn">{{ __('Submit') }}</x-form.button>
        </div>
    </form>
</div>

<!-- <script>
    $(document).on('click', '.checktoggle', function () {
        $('#edit-emp-status').prop('checked', !$('#edit-emp-status').prop('checked'));
    });
</script> -->
