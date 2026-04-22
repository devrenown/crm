<div class="modal-body">
    <form action="{{ route('onboard.identity', ['user_id' => $employeeDetail->user_id]) }}" id="identityForm" method="post" enctype="multipart/form-data">
        @csrf
        
      <div class="row">
          <h5 class="fs-6">Current Address</h5>
          
          <div class="col-lg-6 mb-2">
            <label for="city">City </label>
            <input id="city" name="c_city" value="{{ $employeeDetail->current_city ?? '' }}" type="text" class="form-control">
          </div>
          
          <div class="col-lg-6 mb-2">
            <label for="state">State </label>
            <input id="state" name="c_state" value="{{ $employeeDetail->current_state ?? '' }}" type="text" class="form-control">
          </div>

          <div class="col-lg-6 mb-2">
            <label for="c_address">Address </label>
            <input id="c_address" name="c_address" value="{{ $employeeDetail->current_address ?? '' }}" type="text" class="form-control">
          </div>
          
          <h5 class="mt-3 fs-6">Permanent Address</h5>
          
          <div class="col-lg-6 mb-2">
            <label for="p_city">City </label>
            <input id="p_city" name="p_city" value="{{ $employeeDetail->permanent_city ?? '' }}" type="text" class="form-control">
          </div>

          <div class="col-lg-6 mb-2">
            <label for="p_state">State </label>
            <input id="p_state" name="p_state" value="{{ $employeeDetail->permanent_state ?? '' }}" type="text" class="form-control">
          </div>

          <div class="col-lg-6 mb-2">
            <label for="p_address">Address </label>
            <input id="p_address" name="p_address" value="{{ $employeeDetail->permanent_address ?? '' }}" type="text" class="form-control">
          </div>
          
          <h5 class="mt-3 fs-6">ID Proof <span class="text-muted" style="font-size: 11px !important;">(Allowed
                                    jpg,jpeg,png,webp,pdf &nbsp; max: 2MB )</span></h5>
          
          @if (count($employeeIdList) > 0 ?? [])
            <div id="id-container">
                @foreach ($employeeIdList as $index => $list)
                
                    <div class="row id-item document-block deletable-item">
                        <input type="hidden" name="identy_ids[]" value="{{ $list->id }}">

                        <div class="col-lg-3 mb-2">
                            <label>ID Type *</label>
                            <select name="id_type[]" class="form-select form-control id_type required">
                                <option value="1" {{ $list->id_type == '1' ? 'selected' : '' }}>Adhar Card</option>
                                <option value="2" {{ $list->id_type == '2' ? 'selected' : '' }}>PAN Card</option>
                                <option value="3" {{ $list->id_type == '3' ? 'selected' : '' }}>Voter ID Card</option>
                                <option value="4" {{ $list->id_type == '4' ? 'selected' : '' }}>Driving Licence</option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-2">
                            <label for="id_number">ID Number *</label>
                            <input name="id_number[]" type="text" value="{{ !empty($list->id_number) ? decrypt($list->id_number) : '' }}"
                                class="form-control id_number required text-uppercase">
                        </div>

                        <div class="col-lg-5 mb-2">
                            <label for="id_image">ID Image or PDF* </label>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <input name="id_image[]" type="file" class="form-control id_image"
                                    accept="image/*,application/pdf">

                                <input type="hidden" name="old_ids[]" class="old_ids" value="{{ $list->image ?? '' }}">

                               <a onclick="deleteIdentityId('{{ $list->id }}')" class="delete-icon"><i class="fa-regular fa-trash-can"></i></a>
                            </div>

                            <div class="">
                                @if(!empty($list->image))
                                    @php
                                        $signedUrl = URL::signedRoute(
                                            'secure.document.view',
                                            [
                                                'path'     => encrypt($list->image),
                                                'mime'     => $list->document_mime ?? 'application/pdf',
                                                'filename' => $list->id_name ?? basename($list->image),
                                                'mode'     => 'watermark'
                                            ],
                                            now()->addMinutes(5)
                                        );
                                    @endphp

                                    <div class="row">
                                        <div class="col-md-8">
                                            <a href="javascript:void(0);"
                                                onclick="openSecureDocument('{{ $signedUrl }}')"
                                                class="d-block mt-1">
                                                {!! \App\Helpers\DocumentStatus::statusBadge($list->documentAction?->status) !!}
                                                View {{ $list->id_name }}
                                            </a>
                                        </div>

                                        <div class="col-md-4 mt-1">
                                            <select class="form-control form-select status-dropdown" name="status[]">
                                                <option value="0" {{ $list->documentAction?->status == 0 ? 'selected' : '' }}>Pending</option>
                                                <option value="1" {{ $list->documentAction?->status == 1 ? 'selected' : '' }}>Verified</option>
                                                <option value="2" {{ $list->documentAction?->status == 2 ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </div>

                                        <div class="col-12 remarks-container" style="{{ $list->documentAction?->status == 2 ? '' : 'display:none;' }}">
                                            <span>Remarks</span> 
                                            <input type="text" value="{{ @$list->documentAction?->remark ?? '' }}" name="remarks[]" class="form-control">
                                        </div>

                                        @if ($list->actionBy)
                                        <small class="text-muted">By {{ $list->actionBy?->fullname .' ('. tz($list->documentAction?->action_at, 'd M Y' ) . ')'}}</small>
                                        @endif
                                    </div>

                                @endif
                                
                            </div>
                        </div>

                    </div>
                
                @endforeach
            </div>
            @else
            <div id="id-container">
                <div class="row id-item">
                    <!-- <input type="hidden" name="identy_ids[]" value=""> -->

                    <div class="col-lg-3 mb-2">
                        <label>ID Type *</label>
                        <select name="id_type[]" class="form-select form-control id_type required">
                            <option value="1">Adhar Card</option>
                            <option value="2">PAN Card</option>
                            <option value="3">Voter ID Card</option>
                            <option value="4">Driving Licence</option>
                        </select>
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label>ID Number *</label>
                        <input name="id_number[]" type="text"
                            class="form-control id_number required text-uppercase">
                    </div>

                    <div class="col-lg-5 mb-2">
                        <label>ID Image or PDF *</label>
                        <input name="id_image[]" type="file"
                            class="form-control id_image"
                            accept="image/*,application/pdf" required>
                    </div>
                </div>
            </div>
        @endif

        <div class="add-more">
            <a onclick="repeatIdRow()" type="button"><i class="fa fa-plus-circle"></i>
                {{ __('Add More') }}</a>
        </div>
          
      </div>
      
      <div class="submit-section my-3">
        <button type="submit" class="btn btn-primary submit-btn">{{ __('Update') }}</button>
      </div>
    </form>
</div>

  <script>
    
    $('#identityForm').on('submit', function (e) {
        e.preventDefault();

        const form      = $(this);
        const url       = form.attr('action');
        const formData  = new FormData(this);
        const submitBtn = form.find('button[type=submit]');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,

            beforeSend: function () {
                submitBtn.prop('disabled', true).text('Updating...');
            },
            success: function (res) {

                alert(res.message);

                if (res.status === 200) {
                    form.closest('.modal').modal('hide');
                }
            },
            error: function (xhr) {
                alert(xhr.responseText);
                submitBtn.prop('disabled', false).text('Update');
            },
            complete: function () {
                submitBtn.prop('disabled', false).text('Update');
            }
        })
    })

    function repeatIdRow() {
        let row = `
            <div class="row id-item deletable-item align-items-center">
            <div class="col-lg-3 mb-2">
                <label>ID Type *</label>
                <select name="id_type[]" class="form-select form-control id_type required necessary">
                    <option value="" selected disabled>--Select ID--</option>
                    <option value="1">Adhar Card</option>
                    <option value="2">PAN Card</option>
                    <option value="3">Voter ID Card</option>
                    <option value="4">Driving Licence</option>
                    <option value="5">Passport</option>
                </select>
            </div>

            <div class="col-lg-4 mb-2">
                <label for="id_number">ID Number *</label>
                <input name="id_number[]" type="text" class="form-control id_number required necessary text-uppercase">
            </div>

            <div class="col-lg-5 mb-2">
                <label for="id_image">ID Image or PDF* </label>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <input name="id_image[]" type="file" class="form-control id_image required necessary"
                    accept="image/*,application/pdf">

                    <a href="javascript:void(0);" class="delete-icon"><i class="fa-regular fa-trash-can"></i></a>
                </div>
            </div>

            </div>
            `

        $('#id-container').append(row);
    };

  </script>


