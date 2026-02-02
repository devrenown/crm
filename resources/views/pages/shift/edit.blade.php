<div class="modal-body">
    <form action="{{ route('shift.update', ['shift' => $shift->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">

            <div class="col-md-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Shift Name') }}</x-form.label>
                    <x-form.input type="text" name="name" placeholder="e.g. General / Morning / Night" value="{{ $shift->name }}" required />
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Start Time') }}</x-form.label>
                    <x-form.input type="time" name="start_time"  value="{{ $shift->start_time }}" required />
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('End Time') }}</x-form.label>
                    <x-form.input type="time" name="end_time" value="{{ $shift->end_time }}" required />
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Break Minutes') }}</x-form.label>
                    <x-form.input type="number" name="break_minutes" min="0" value="{{ $shift->break_minutes ?? 0 }}" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Grace Minutes') }}</x-form.label>
                    <x-form.input type="number" name="grace_minutes" min="0" value="{{ $shift->grace_minutes ?? 0 }}" />
                </div>
            </div>

            <div class="col-md-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Status') }}</x-form.label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $shift->status == 1 ? 'selected' : '' }}>Active</option>
                        <option value="2" {{ $shift->status == 2 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

        </div>

        <div class="submit-section my-2">
            <x-form.button class="btn btn-primary submit-btn" type="submit">
                {{ __('Update Shift') }}
            </x-form.button>
        </div>
    </form>
</div>
