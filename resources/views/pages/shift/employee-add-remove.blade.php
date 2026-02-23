<div class="modal-body">
    <form action="{{ route('shift.add-remove-employee') }}" method="post">
        @csrf

        <input type="hidden" value="{{ $shift->id }}" name="shift_id">
        <div class="row">
            
            <div class="col-12">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Shift') }}
                    </x-form.label>

                    <select name="shift" class="form-control" required>
                        <option value="{{ $shift->id }}" selected>{{ $shift->name }}</option>
                    </select>
                           
                </x-form.input-block>
            </div>

            <div class="col-12">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Add/Remove Employees') }}
                    </x-form.label>

                    @php
                      $employeesShift = \App\Models\User::whereHas('shift', fn ($q) => $q->where('shift_id', $shift->id))->get();
                      $employeesShiftIds = $employeesShift->pluck('id')->toArray();
                    @endphp
                    <select name="employee[]" class="form-control select" data-placeholder="{{ __('Add/Remove Employees') }}" multiple required>
                        @if (!empty($employees))
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}" {{ in_array($employee->id, $employeesShiftIds) ? 'selected' : '' }}>{{ $employee->fullname }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>
            
        </div>

        <div class="submit-section mb-3">
            <button class="btn btn-primary submit-btn">{{ __('Save') }}</button>
        </div>
    </form>
</div>
