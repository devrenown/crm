<div class="modal-body">
  <form action="{{ route('employee.education.update', $employeeDetail->id) }}" method="post" enctype="multipart/form-data" class="repeater">
      @csrf
      <div class="form-scroll">
          <div data-repeater-list="education">
              @if (!empty($educations) && ($educations->count() > 0))
                  @foreach ($educations as $i => $education)
                  <div data-repeater-item class="card">
                    <input type="hidden" name="id" value="{{ $education->id }}">
                    <div class="card-body">
                        <h3 class="card-title">
                            {{ __('Education Information') }}
                            <a class="delete-icon deleteBtn" data-route="{{ route('employee.education.delete',['education' => $education->id, 'user_id' => $employeeDetail->user_id])  }}" 
                                data-title="Delete Education"
                                data-question="Are you sure you want to delete?" data-repeater-delete type="button" href="javascript:void(0)">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                           
                        </h3>
                        <div class="row">
                          
                            <div class="col-md-6">
                                <x-form.label class="mb-0"> {{ __('Course/Certification') }}</x-form.label>
                                <select class="form-select form-control courses required necessary mb-0" name="course">
                                    <option value="10th" {{ $education->course == '10th' ? 'selected' : '' }}>10th</option>
                                    <option value="12th" {{ $education->course == '12th' ? 'selected' : '' }}>12th</option>
                                    <option value="diploma" {{ $education->course == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="graduation" {{ $education->course == 'graduation' ? 'selected' : '' }}>Graduation</option>
                                    <option value="post graduation" {{ $education->course == 'post graduation' ? 'selected' : '' }}>Post Graduation</option>
                                    <option value="other" {{ $education->course == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label> {{ __('University/Institution/Board') }}</x-form.label>
                                    <x-form.input type="text" name="institution" value="{{ $education->institution ?? old('institution') }}" />
                                </x-form.input-block>
                            </div>
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label> {{ __('Subject/Branch/Specialization') }}</x-form.label>
                                    <x-form.input type="text" name="subject" value="{{ $education->subject ?? old('subject') }}" />
                                </x-form.input-block>
                            </div>
  
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label> {{ __('Grade/Percentage') }}</x-form.label>
                                    <x-form.input type="text" name="grade" value="{{ $education->grade ?? old('grade') }}" />
                                </x-form.input-block>
                            </div>
  
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label calss="focus-label"> {{ __('Starting Date') }}</x-form.label>
                                    <div class="cal-icon">
                                        <x-form.input type="text" class="datepicker" name="start_date" value="{{ $education->start_date ?? old('start_date') }}" />
                                    </div>
                                </x-form.input-block>
                            </div>
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label calss="focus-label"> {{ __('Date Completed') }}</x-form.label>
                                    <div class="cal-icon">
                                        <x-form.input type="text" class="datepicker" name="end_date" value="{{ $education->end_date ?? old('end_date') }}" />
                                    </div>
                                </x-form.input-block>
                            </div>

                            <div class="row pe-0 document-block">

                                <div class="col-md-6">
                                    <x-form.input-block>
                                        <x-form.label> {{ __('File') }}</x-form.label>
                                        <x-form.input type="file" name="file" />
                                        <input type="hidden" name="old_file" value="{{ $education->file ?? '' }}">

                                        @if(!empty($education->file))
                                            @php
                                                $signedUrl = URL::signedRoute(
                                                    'secure.document.view',
                                                    [
                                                        'path'     => encrypt($education->file),
                                                        'mime'     => $education->document_mime ?? 'application/pdf',
                                                        'filename' => basename($education->file),
                                                        'mode'     => 'watermark'
                                                    ],
                                                    now()->addMinutes(5)
                                                );
                                            @endphp

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <a href="javascript:void(0);"
                                                        onclick="openSecureDocument('{{ $signedUrl }}')"
                                                        class="d-block mt-1 view-edu-file">
                                                        {!! \App\Helpers\DocumentStatus::statusBadge($education->documentAction?->status) !!}
                                                        View File
                                                    </a>
                                                </div>

                                            </div>

                                        @endif
                                    </x-form.input-block>
                                </div>

                                <div class="col-md-6 document-action">
                                    <x-form.label> {{ __('Document Status') }}</x-form.label>
                                    <div>
                                        <select class="form-control form-select status-dropdown" name="status">
                                            <option value="0" {{ $education->documentAction?->status == 0 ? 'selected' : '' }}>Pending</option>
                                            <option value="1" {{ $education->documentAction?->status == 1 ? 'selected' : '' }}>Verified</option>
                                            <option value="2" {{ $education->documentAction?->status == 2 ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>

                                    <div class="remarks-container" style="{{ $education->documentAction?->status == 2 ? '' : 'display:none;' }}">
                                        <span>Remarks</span> 
                                        <input type="text" value="{{ @$education->documentAction?->remark ?? '' }}" name="remarks" class="form-control">
                                    </div>

                                    @if ($education->actionBy)
                                    <small class="text-muted">By {{ $education->actionBy?->fullname .' ('. tz($education->documentAction?->action_at, 'd M Y' ) . ')'}}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>  
                  </div>
                  @endforeach
              @else
              <div data-repeater-item class="card">
                  <div class="card-body">
                      <h3 class="card-title">
                          {{ __('Education Information') }}
                          <a href="javascript:void(0);" data-repeater-delete type="button" class="delete-icon"
                          ><i class="fa-regular fa-trash-can"></i></a>
                      </h3>
                      <div class="row">
                          
                          <div class="col-md-6">
                                <x-form.label class="mb-0"> {{ __('Course/Certification') }}</x-form.label>
                                <select class="form-select form-control courses required necessary mb-0" name="course">
                                    <option value="10th">10th</option>
                                    <option value="12th">12th</option>
                                    <option value="diploma">Diploma</option>
                                    <option value="graduation">Graduation</option>
                                    <option value="post graduation">Post Graduation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label> {{ __('University/Institution/Board') }}</x-form.label>
                                    <x-form.input type="text" name="institution" value="{{ old('institution') }}" />
                                </x-form.input-block>
                            </div>
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label> {{ __('Subject/Branch/Specialization') }}</x-form.label>
                                    <x-form.input type="text" name="subject" value="{{ old('subject') }}" />
                                </x-form.input-block>
                            </div>
  
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label> {{ __('Grade/Percentage') }}</x-form.label>
                                    <x-form.input type="text" name="grade" value="{{ old('grade') }}" />
                                </x-form.input-block>
                            </div>
  
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label calss="focus-label"> {{ __('Starting Date') }}</x-form.label>
                                    <div class="cal-icon">
                                        <x-form.input type="text" class="datepicker" name="start_date" value="{{ old('start_date') }}" />
                                    </div>
                                </x-form.input-block>
                            </div>
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label calss="focus-label"> {{ __('Date Completed') }}</x-form.label>
                                    <div class="cal-icon">
                                        <x-form.input type="text" class="datepicker" name="end_date" value="{{ old('end_date') }}" />
                                    </div>
                                </x-form.input-block>
                            </div>
                            <div class="col-md-6">
                                <x-form.input-block>
                                    <x-form.label> {{ __('File') }}</x-form.label>
                                    <x-form.input type="file" name="file" />
                                </x-form.input-block>
                            </div>
                        </div>
                  </div>
              </div>
              @endif
          </div>
      </div>
      <div class="add-more">
          <a href="javascript:void(0);" data-repeater-create type="button"><i class="fa fa-plus-circle"></i> {{ __('Add More') }}</a>
      </div>
      <div class="submit-section my-3">
          <button type="submit" id="eduSaveBtn" class="btn btn-primary submit-btn">
            <span class="btn-text">Submit</span>
            <span class="btn-loader" style="display:none;">
                <i class="fa fa-spinner fa-spin"></i> Saving...
            </span>
        </button>
      </div>
  </form>
</div>

<script type="module" defer>
  $(document).ready(function(){

    $('.repeater').on('submit', function () {

        let btn = $('#eduSaveBtn');

        btn.prop('disabled', true);
        btn.find('.btn-text').hide();
        btn.find('.btn-loader').show();

    });

    $('.repeater').repeater({

        show: function () {

            // Remove DB delete button from cloned row
            $(this).find('.deleteBtn').remove();
            $(this).find('.view-edu-file').remove();
            $(this).find('.document-action').remove();

            // Add repeater delete button if not exists
            if ($(this).find('.repeater-delete').length === 0) {
                $(this).find('.card-title').append(`
                    <a href="javascript:void(0);"
                       data-repeater-delete
                       type="button"
                       class="delete-icon repeater-delete">
                       <i class="fa-regular fa-trash-can"></i>
                    </a>
                `);
            }

            $(this).slideDown();

            // $('.datepicker').datetimepicker('destroy');

            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
                icons: {
                    up: "fa fa-angle-up",
                    down: "fa fa-angle-down",
                    next: 'fa fa-angle-right',
                    previous: 'fa fa-angle-left'
                }
            });
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }

    });

});
</script>

