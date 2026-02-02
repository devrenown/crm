<div class="modal-body">
    <form action="{{ route('work-report.update') }}" method="post">
      @csrf
      <input type="hidden" name="task_id" value="{{ $task->id }}">

        <div class="row">
            <div class="col-md-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Title') }}</x-form.label>
                    <x-form.input type="text" name="title" value="{{ $task->title }}" />
                </div>
            </div>

            <div x-data="{ forProject: {{ isset($task->project_id) ? 'true' : 'false' }} }">
                <x-form.input-block>
                    <div class="status-toggle">
                        <x-form.label>{{ __('For Project ?') }}</x-form.label>

                        <x-form.input 
                            type="checkbox" 
                            id="forProject" 
                            class="check"
                            name="forProject"
                            wire:model="forProject"
                            x-model="forProject"
                            @click="forProject = !forProject"
                        />
                        <label for="forProject" class="checktoggle">checkbox</label>
                    </div>
                </x-form.input-block>

                <template x-if="forProject">
                    <x-form.input-block>
                        <x-form.label required>{{ __('Project') }}</x-form.label>
                        <select class="form-control" name="project" wire:model="project">
                            <option value="">{{ __('Select Project') }}</option>
                            @foreach (\Modules\Project\Models\Project::get() as $project)
                                <option 
                                    value="{{ $project->id }}" 
                                    {{ $task->project_id == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </x-form.input-block>
                </template>
            </div>


            <div class="col-12">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Work Status') }}</x-form.label>
                    <select class="form-control" name="status">
                        <option value="" disabled selected>{{ __('-- Select Status --') }}</option>
                        @foreach (\App\Enums\TaskStatus::cases() as $item)
                            <option value="{{ $item->value }}" {{ $item->value == @$task->status->value ? 'selected' : '' }}>{{ $item->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Description') }}</label>
                    <x-form.ckeditor name="description" id="editor">{!! $task->description ?? '' !!}</x-form.ckeditor>
                </div>
            </div>
        </div>

        <div class="submit-section my-2">
            <x-form.button class="btn btn-primary submit-btn" type="submit">{{ __('Submit') }}</x-form.button>
        </div>
    </form>
</div>

