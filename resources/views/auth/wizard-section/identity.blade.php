@php
    $path = public_path('upload/employee_ids/');
    $url = asset('upload/employee_ids/');
@endphp

<h3>Identification</h3>
<section style="overflow-y:scroll; overflow-x: hidden;">
    <div>
        <h5 class="mb-2 fs-5">Current Address</h5>
        <div class="row">

            <div class="col-lg-4 mb-2">
                <label for="c_address">Address* </label>
                <input id="c_address" name="c_address" value="{{ $userDetails->current_address ?? '' }}" type="text"
                    class="form-control required necessary" placeholder="Enter your full current address ">
            </div>

            <div class="col-lg-4 mb-2">
                <label for="city">City *</label>
                <input id="city" name="city" value="{{ $userDetails->current_city ?? '' }}" type="text"
                    class="form-control required necessary">
            </div>

            <div class="col-lg-4 mb-2">
                <label for="state">State* </label>
                <input id="state" name="state" value="{{ $userDetails->current_state ?? '' }}" type="text"
                    class="form-control required necessary">
            </div>

        </div>

        <div>

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-2 fs-5 p-address-heading">Permanent Address</h5>
                <div class="col-lg-2 mb-2 d-flex justify-content-center align-items-center">
                    <label for="same_address">Mark as Parmanent Address</label>
                    <input onchange="permanentAddressVisiblity()" id="same_address" name="same_address" type="checkbox"
                        class="form-check ms-1">
                </div>
            </div>

            <div class="row" id="permanent-address">

                <div class="col-lg-4 mb-2">
                    <label for="p_address">Address* </label>
                    <input id="p_address" name="p_address" value="{{ $userDetails->permanent_address ?? '' }}" type="text" class="form-control required necessary" placeholder="Enter your full permanent address ">
                </div>

                <div class="col-lg-4 mb-2">
                    <label for="p_city">City *</label>
                    <input id="p_city" name="p_city" value="{{ $userDetails->permanent_city ?? '' }}" type="text"
                        class="form-control required necessary">
                </div>

                <div class="col-lg-4 mb-2">
                    <label for="p_state">State* </label>
                    <input id="p_state" name="p_state" value="{{ $userDetails->permanent_state ?? '' }}" type="text"
                        class="form-control required necessary">
                </div>

            </div>

        </div>
        
        {{-- dd( $employeeIdList) --}}

        <div id="id-container">

            @if (count($employeeIdList) > 0)

                @php
                    $pan = $employeeIdList->firstWhere('id_type', '2');
                    $addressProofs = $employeeIdList->where('id_type', '!=', '2');
                @endphp

                <h5 class="mb-2 fs-5 mt-3">Identity Proof</h5>

                <div class="row id-item">
                    <input type="hidden" name="identy_ids[]" class="identy_ids" value="{{ $pan->id ?? '' }}">

                    <div class="col-lg-6 col-md-6">
                        <label>PAN No.*</label>
                        <input type="hidden" name="id_type[]" class="id_type" value="2">
                        <input type="text" name="id_number[]" class="form-control id_number required necessary text-uppercase" value="{{ !empty($pan->id_number) ? decrypt($pan->id_number) : '' }}">
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <label>PAN Image or PDF*</label>
                        <input type="file" name="id_image[]" class="form-control id_image {{ empty($pan->image) ? 'required' : '' }} necessary">

                        <input type="hidden" name="old_ids[]" class="old_ids" value="{{ $pan->image ?? '' }}">

                        @if(!empty($pan) && file_exists($path . $pan->image))
                           <i class="fa-solid fa-check-circle text-success me-1"></i> <a href="{{ $url . '/' . $pan->image }}" target="_blank">View PAN</a>
                        @endif

                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed jpg,jpeg,png,webp,pdf (max 2MB)</small>
                    </div>
                </div>

                @foreach ($addressProofs as $index => $list)

                    <h5 class="mb-2 fs-5 mt-3">Address Proof</h5> 

                    <div class="row id-item deletable-item">

                        <input type="hidden" name="identy_ids[]" class="identy_ids" value="{{ $list->id }}">
                        <div class="col-lg-4 mb-2">
                            <label>ID Type *</label>
                            <select name="id_type" class="form-select form-control id_type required necessary">
                                <option value="" selected disabled>--Select ID--</option>
                                <option value="1" {{ $list->id_type == '1' ? 'selected' : '' }}>Adhar Card</option>
                                <option value="3" {{ $list->id_type == '3' ? 'selected' : '' }}>Voter ID Card</option>
                                <option value="4" {{ $list->id_type == '4' ? 'selected' : '' }}>Driving Licence</option>
                                <option value="5" {{ $list->id_type == '5' ? 'selected' : '' }}>Passport</option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-2">
                            <label for="id_number">ID Number *</label>
                            <input name="id_number" type="text" value="{{ !empty($list->id_number) ? decrypt($list->id_number) : '' }}"
                                class="form-control id_number required necessary text-uppercase">
                        </div>

                        <div class="col-lg-4 mb-2">
                            <label for="id_image">ID Image or PDF* </label>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <input name="id_image" type="file" data-file="{{ @$list->image ?? '' }}" class="form-control id_image necessary"
                                    accept="image/*,application/pdf">

                                <input type="hidden" name="old_ids[]" class="old_ids" value="{{ $list->image ?? '' }}">

                                <a onclick="deleteIdentityId('{{ $list->id }}')" class="delete-icon"><i
                                        class="fa-regular fa-trash-can"></i></a>
                            </div>

                            <div class="">
                                @php
                                    $filePath = $path . $list->image;
                                    $fileUrl = $url . '/' . $list->image;
                                    $filePath = public_path('upload/employee_ids/' . $list->image);
                                    $mime = mime_content_type($filePath);
                                    
                                    $signedUrl = URL::signedRoute('secure.document.view', now()->addMinutes(3), [
                                        'path' => encrypt($filePath),
                                        'mime' => $mime,
                                        'filename' => $list->image,
                                    ]);
                                @endphp
                                @if ($list->image && file_exists($filePath))
                                    <i class="fa-solid fa-check-circle text-success me-1"></i> <a href="javascript:void(0);"
                                        onclick="openSecureDocument('{{ $signedUrl }}')">
                                        View {{ $list->id_name }}
                                        </a>
                                    
                                @endif
                                <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                                    jpg,jpeg,png,webp,pdf &nbsp; max: 2MB</small>
                            </div>
                        </div>

                    </div>
                @endforeach

            @else

                <h5 class="mb-2 fs-5 mt-3">Identity Proof</h5> 
                       
                <div class="row id-item">
                    <div class="col-lg-6 col-md-6">
                        <label>PAN No.*</label>
                        <input type="hidden" name="id_type" class="id_type" value="2">
                        <input type="text" name="id_number" class="form-control id_number required necessary text-uppercase">
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <label>PAN image or PDF*</label>
                        <input type="file" name="id_image" class="form-control id_image required necessary">

                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                            jpg,jpeg,png,webp,pdf
                            &nbsp; max: 2MB</small>

                    </div>
                </div>

                <h5 class="mb-2 fs-5 mt-3">Address Proof</h5> 

                <div class="row id-item">

                    <div class="col-lg-4 mb-2">
                        <label>ID Type *</label>
                        <select name="id_type" class="form-select form-control id_type required necessary">
                            <option value="1">Adhar Card</option>
                            <option value="3">Voter ID Card</option>
                            <option value="4">Driving Licence</option>
                            <option value="5">Passport</option>
                        </select>
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label for="id_number">ID Number *</label>
                        <input name="id_number" value="{{ old('id_number') }}" type="text"
                            class="form-control id_number required necessary text-uppercase">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label for="id_image">ID Image or PDF* </label>
                        <input name="id_image" type="file" class="form-control id_image required necessary" accept="image/*,application/pdf">

                        <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                            jpg,jpeg,png,webp,pdf
                            &nbsp; max: 2MB</small>
                       
                    </div>
                </div>
            @endif

        </div>

        <div class="add-more">
            <a onclick="repeatIdRow()" type="button"><i class="fa fa-plus-circle"></i>
                {{ __('Add More') }}</a>
        </div>


    </div>

</section>

@push('page-scripts')

    <script>
        function repeatIdRow() {
            let row = `
                    <div class="row id-item deletable-item align-items-center">
                    <div class="col-lg-4 mb-2">
                        <label>ID Type *</label>
                        <select name="id_type" class="form-select form-control id_type required necessary">
                            <option value="" selected disabled>--Select ID--</option>
                            <option value="1">Adhar Card</option>
                            <option value="3">Voter ID Card</option>
                            <option value="4">Driving Licence</option>
                            <option value="5">Passport</option>
                        </select>
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label for="id_number">ID Number *</label>
                        <input name="id_number" type="text" class="form-control id_number required necessary text-uppercase">
                    </div>

                    <div class="col-lg-4 mb-2">
                        <label for="id_image">ID Image or PDF* </label>
                        <div class="d-flex justify-content-center align-items-center gap-2">
                            <input name="id_image" type="file" class="form-control id_image required necessary"
                            accept="image/*,application/pdf">

                            <a href="javascript:void(0);" class="delete-icon"><i class="fa-regular fa-trash-can"></i></a>
                        </div>

                        <div class="">
                            <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                            jpg,jpeg,png,webp,pdf &nbsp; max: 2MB</small>
                        </div>
                    </div>

                </div>
                `

            $('#id-container').append(row);
        };

        function permanentAddressVisiblity() {
            $('#permanent-address, .p-address-heading').slideToggle();
        }

    </script>

@endpush