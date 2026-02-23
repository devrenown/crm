<div class="modal-body">
    <form action="{{ route('reporting-manager.assign') }}" method="post">
        @csrf
        <div class="row">
            
            <div class="col-12">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Add Reporting Manager') }}
                    </x-form.label>
                    <select name="reporting_manager" class="form-control select" required>
                        <option value="" selected disabled>{{ __('Select Reporting Manager') }}</option>
                        @if (!empty($employees))
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>

            <div class="col-12">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Add Team') }}
                    </x-form.label>
                    <select name="teams[]" class="form-control select" data-placeholder="{{ __('Select Team Member') }}" multiple required>
                        @if (!empty($employees))
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>
            
        </div>

        <div class="submit-section mb-3">
            <button class="btn btn-primary submit-btn">{{ __('Assign') }}</button>
        </div>
    </form>
</div>
