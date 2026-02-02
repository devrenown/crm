<div class="modal-body">
    <form action="{{ route('projects.update', $project->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method("PUT")
        <div class="row">
            <div class="col-md-6">
                <div class="input-block mb-3">
                    <x-form.label>{{ __('Project Name') }}<span class="text-danger">*</span></x-form.label>
                    <x-form.input type="text" name="name"  value="{{ $project->name }}" required />
                </div>
            </div>
            
            <div class="col-sm-6">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Client') }}<span class="text-danger">*</span>
                    </x-form.label>
                    <select name="client" class="form-control" required>
                        @if (!empty($clients))
                            @foreach ($clients as $client)
                                <option {{ $project->client_id == $client->id ? 'selected': '' }} value="{{ $client->id }}">{{ $client->fullname }}</option>
                            @endforeach
                        @endif
                    </select>
                </x-form.input-block>
            </div>
            
            <div class="col-md-6">
                <x-form.input-block>
                    <x-form.label calss="focus-label"> {{ __('Start Date') }}<span class="text-danger">*</span></x-form.label>
                    <div class="cal-icon">
                        <x-form.input type="text" class="datepicker" name="startDate" value="{{ $project->startDate }}" required />
                    </div>
                </x-form.input-block>
            </div>
            <div class="col-md-6">
                <x-form.input-block>
                    <x-form.label calss="focus-label"> {{ __('End Date') }}</x-form.label>
                    <div class="cal-icon">
                        <x-form.input type="text" class="datepicker" name="endDate" value="{{ $project->endDate }}" />
                    </div>
                </x-form.input-block>
            </div>
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="input-block mb-3">
                            <x-form.label>{{ __('Rate') }}<span class="text-danger">*</span></x-form.label>
                            <x-form.input type="text" name="rate" placeholder="10" value="{{ $project->rate ?? 0.00 }}" required />
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <x-form.input-block>
                            <x-form.label>Rate Type<span class="text-danger">*</span></x-form.label>
                            <select class="form-control" name="rateType" required>
                                <option {{ $project->rateType == 'Hourly' ? 'selected': '' }}>{{ __('Hourly') }}</option>
                                <option {{ $project->rateType == 'Fixed' ? 'selected': '' }}>{{ __('Fixed') }}</option>
                            </select>
                        </x-form.input-block>
                    </div>
                    <div class="col-sm-6">
                        <x-form.input-block>
                            <x-form.label>{{ __('Priority') }}<span class="text-danger">*</span></x-form.label>
                            <select name="priority" class="form-control" required>
                                <option {{ $project->priority == 'High' ? 'selected': '' }}>{{ __('High') }}</option>
                                <option {{ $project->priority == 'Medium' ? 'selected': '' }}>{{ __('Medium') }}</option>
                                <option {{ $project->priority == 'Low' ? 'selected': '' }}>{{ __('Low') }}</option>
                            </select>
                        </x-form.input-block>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <x-form.input-block>
                    <x-form.label>
                        {{ __('Add Project Leader') }}
                    </x-form.label>
                    <select name="leader" class="form-control">
                        @if (!empty($employees))
                            @foreach ($employees as $employee)
                                @if(activeRole() === \App\Enums\UserType::TL->value)
                                    @if($employee->id == auth()->id())
                                    <option value="{{ $employee->id }}" selected>{{ $employee->fullname}}</option>
                                    @endif
                                @else
                                    <option {{ $project->leader_id == $employee->id ? 'selected': '' }} value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                @endif
                                
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
                    <select name="team[]" class="form-control select" data-placeholder="{{ __('Select Team Member') }}" multiple>
                        @if (!empty($employees))
                            @foreach ($employees as $employee)

                                @if(activeRole() === \App\Enums\UserType::TL->value)

                                    @if($employee->reporting_manager == auth()->id())
                                        <option
                                            value="{{ $employee->id }}"
                                            {{ in_array($employee->id, $project->team->pluck('user_id')->toArray()) ? 'selected' : '' }}>
                                            {{ $employee->fullname }}
                                        </option>
                                    @endif

                                @else

                                    <option
                                        value="{{ $employee->id }}"
                                        {{ in_array($employee->id, $project->team->pluck('user_id')->toArray()) ? 'selected' : '' }}>
                                        {{ $employee->fullname }}
                                    </option>

                                @endif

                            @endforeach
                        @endif

                    </select>
                </x-form.input-block>
            </div>
            <div class="col-12">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Short Description') }}<span class="text-danger">*</span></label>
                    <x-form.textarea name="short_desc" required>{{ $project->short_desc }}</x-form.textarea>
                </div>
            </div>
            <div class="col-12">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Brief Description') }}</label>
                    <x-form.ckeditor name="description" id="editor">{{ $project->description }} </x-form.ckeditor>
                </div>
            </div>
            <div class="col-12">
                <div class="input-block mb-3">
                    <label class="col-form-label">{{ __('Files') }}</label>
                    <x-form.input type="file" name="projectFiles[]" multiple />
                </div>
            </div>
            
        </div>
        <div class="submit-section mb-3">
            <button class="btn btn-primary submit-btn">{{ __('Submit') }}</button>
        </div>
    </form>
</div>

