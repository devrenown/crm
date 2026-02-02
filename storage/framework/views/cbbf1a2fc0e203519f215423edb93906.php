<div class="modal-body">
    <form action="<?php echo e(route('onboard.identity', ['user_id' => $employeeDetail->user_id])); ?>" id="identityForm" method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        
        <?php
            $path = public_path('upload/employee_ids/');
            $url = asset('upload/employee_ids/');
        ?>
        
      <div class="row">
          <h5 class="fs-6">Current Address</h5>
          
          <div class="col-lg-6 mb-2">
            <label for="city">City </label>
            <input id="city" name="c_city" value="<?php echo e($employeeDetail->current_city ?? ''); ?>" type="text" class="form-control">
          </div>
          
          <div class="col-lg-6 mb-2">
            <label for="state">State </label>
            <input id="state" name="c_state" value="<?php echo e($employeeDetail->current_state ?? ''); ?>" type="text" class="form-control">
          </div>

          <div class="col-lg-6 mb-2">
            <label for="c_address">Address </label>
            <input id="c_address" name="c_address" value="<?php echo e($employeeDetail->current_address ?? ''); ?>" type="text" class="form-control">
          </div>
          
          <h5 class="mt-3 fs-6">Permanent Address</h5>
          
          <div class="col-lg-6 mb-2">
            <label for="p_city">City </label>
            <input id="p_city" name="p_city" value="<?php echo e($employeeDetail->permanent_city ?? ''); ?>" type="text" class="form-control">
          </div>

          <div class="col-lg-6 mb-2">
            <label for="p_state">State </label>
            <input id="p_state" name="p_state" value="<?php echo e($employeeDetail->permanent_state ?? ''); ?>" type="text" class="form-control">
          </div>

          <div class="col-lg-6 mb-2">
            <label for="p_address">Address </label>
            <input id="p_address" name="p_address" value="<?php echo e($employeeDetail->permanent_address ?? ''); ?>" type="text" class="form-control">
          </div>
          
          <h5 class="mt-3 fs-6">ID Proof</h5>
          
          <?php if(count($employeeIdList) > 0 ?? []): ?>

                <?php $__currentLoopData = $employeeIdList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="row id-item deletable-item">
                        <input type="hidden" name="identy_ids[]" value="<?php echo e($list->id); ?>">

                        <div class="col-lg-4 mb-2">
                            <label>ID Type *</label>
                            <select name="id_type[]" class="form-select form-control id_type required">
                                <option value="" selected disabled>--Select ID--</option>
                                <option value="1" <?php echo e($list->id_type == '1' ? 'selected' : ''); ?>>Adhar Card</option>
                                <option value="2" <?php echo e($list->id_type == '2' ? 'selected' : ''); ?>>PAN Card</option>
                                <option value="3" <?php echo e($list->id_type == '3' ? 'selected' : ''); ?>>Voter ID Card</option>
                                <option value="4" <?php echo e($list->id_type == '4' ? 'selected' : ''); ?>>Driving Licence</option>
                                <option value="5" <?php echo e($list->id_type == '5' ? 'selected' : ''); ?>>Vaccination Certificate</option>
                            </select>
                        </div>

                        <div class="col-lg-4 mb-2">
                            <label for="id_number">ID Number *</label>
                            <input name="id_number[]" type="text" value="<?php echo e(!empty($list->id_number) ? decrypt($list->id_number) : ''); ?>"
                                class="form-control id_number required text-uppercase">
                        </div>

                        <div class="col-lg-4 mb-2">
                            <label for="id_image">ID Image or PDF* </label>
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                <input name="id_image[]" type="file" class="form-control id_image"
                                    accept="image/*,application/pdf">

                                <input type="hidden" name="old_ids[]" class="old_ids" value="<?php echo e($list->image ?? ''); ?>">

                               
                            </div>

                            <div class="">
                                <?php
                                    $filePath = $path . $list->image;
                                    $fileUrl = $url . '/' . $list->image;
                                ?>
                                <?php if($list->image && file_exists($filePath)): ?>
                                    <a href="<?php echo e($fileUrl); ?>" target="_blank">View <?php echo e($list->id_name); ?></a>
                                    <!-- <a href="<?php echo e(asset('js/plugins/pdfjs/web/viewer.html')); ?>?file=<?php echo e(urlencode($fileUrl)); ?>" 
                                       target="_blank">
                                       View <?php echo e($list->id_name ?? ''); ?>

                                    </a> -->
                                <?php endif; ?>
                                <small class="text-muted d-block"><span class="text-danger">*</span>Allowed
                                    jpg,jpeg,png,webp,pdf &nbsp; max: 2MB</small>
                            </div>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php endif; ?>
          
      </div>
      
      <div class="submit-section my-3">
        <button type="submit" class="btn btn-primary submit-btn"><?php echo e(__('Update')); ?></button>
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
                console.log(res)

                alert(res.message + ' ✅');

                form.closest('.modal').modal('hide');
                toastr.success(res.message);
            },
            error: function (xhr) {
                console.log(xhr.responseText)
                toastr.error('Something went wrong, please try again.');
            },
            complete: function () {
                submitBtn.prop('disabled', false).text('Update');
            }
        })
    })

  </script>


<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/pages/employees/modals/identity.blade.php ENDPATH**/ ?>