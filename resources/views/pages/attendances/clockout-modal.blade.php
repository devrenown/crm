<div class="modal-body">
    {{-- <form wire:submit.prevent="clockout" method="post"> --}}
    <form action="{{ route('clockout') }}" method="post">
      @csrf
      <input type="hidden" name="timestampId" value="{{ request('timeId')}}">
      {{-- <input type="hidden" wire:model.defer="timestampId" name="timestampId" value="{{ request('timeId')}}"> --}}

        <div class="row">
            <div class="col-md-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Title') }}</x-form.label>
                    <x-form.input type="text" name="title" :required="true" />
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div x-data="{project: false}">
                <x-form.input-block>
                <div class="status-toggle">
                    <x-form.label>{{ __('For Project ?') }}</x-form.label>
                    <x-form.input type="checkbox" id="project" class="check" name="project_check" @click="project = !project" wire:model="project" />
                    <label for="project" class="checktoggle">checkbox</label>
                </div>
                </x-form.input-block>
                <div x-show="project">
                    <x-form.input-block>
                        <x-form.label required>{{ __('Project') }}</x-form.label>
                        <select class="form-control" name="project" wire:model="project">
                            <option value="">{{ __('Select Project') }}</option>
                            @foreach (\Modules\Project\Models\Project::get() as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </x-form.input-block>
                </div>
            </div>

            <div class="col-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Work Status') }}</x-form.label>
                    <select class="form-control" name="status">
                        <option value="" disabled selected>{{ __('-- Select Status --') }}</option>
                        @foreach (\App\Enums\TaskStatus::cases() as $item)
                            <option value="{{ $item->value }}">{{ $item->label() }}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Description') }}</label>
                    <x-form.ckeditor name="description" id="editor"></x-form.ckeditor>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="submit-section my-2">
            <button type="submit" class="btn btn-primary submit-btn">
                <span class="btn-text">Submit</span>
                <span class="spinner-border spinner-border-sm d-none"></span>
            </button>
        </div>
    </form>
</div>

<script>
document.querySelector("form").addEventListener("submit", function(e) {
    let form = this;

    if (!form.checkValidity()) return;

    let btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;

    btn.querySelector('.btn-text').innerText = "Submitting...";
    btn.querySelector('.spinner-border').classList.remove('d-none');
});
</script>

