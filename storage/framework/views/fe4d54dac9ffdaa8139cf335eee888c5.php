<div>
    <!--[if BLOCK]><![endif]--><?php if(!$showAsset): ?>
    <div class="table-responsive table-newdatatable">
        <table class="table table-new custom-table mb-0 datatable">
          <thead>
            <tr>
              <th>#</th>
              <th><?php echo e(__('Name')); ?></th>
              <th><?php echo e(__('Asset ID')); ?></th>
              <th><?php echo e(__('Assigned Date')); ?></th>
              <th><?php echo e(__('Assignee')); ?></th>
              <th><?php echo e(__('Action')); ?></th>
            </tr>
          </thead>
          <tbody>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $user->assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
              <td><?php echo e(++$key); ?></td>
              <td>
                <a href="assets-details.html" class="table-imgname">
                  <span><?php echo e($asset->name); ?></span>
                </a>
              </td>
              <td><?php echo e($asset->ast_id); ?></td>
              <td><?php echo e(format_date($asset->created_at)); ?></td>
              <td class="table-namesplit">
                <a href="javascript:void(0);" class="table-name">
                  <span><?php echo e($asset->createdBy->fullname ?? ''); ?></span>
                  <p><?php echo e($asset->createdBy->email ?? ''); ?></p>
                </a>
              </td>
              <td>
                <div class="table-actions d-flex" wire:click="viewAsset(<?php echo e($asset->id); ?>)">
                  <a
                    class="delete-table me-2"
                    href="javascript:void(0)"
                  >
                  <img
                      src="<?php echo e(asset('images/icons/eye.svg')); ?>"
                      alt="Eye Icon"
                    />
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
          </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="assign-head">
        <div class="assign-content">
            <h6><?php echo e($asset->name); ?></h6>
        </div>
        <div class="assign-content" x-data>
            <?php
                $profileUrl = route('employees.show', 
            ['employee' => Crypt::encrypt($asset->user->id)]);
            ?>
            <button type="button" class="btn btn-assign me-2" @click="window.location.href='<?php echo e($profileUrl); ?>'"><?php echo e(__('Assets')); ?></button>
            <a href="#" class="btn btn-assign" data-bs-toggle="modal" data-bs-target="#raise-issue"><i class="fas fa-hand-paper"></i> Raise Issue  </a>
        </div>
    </div>
    <div class="card asset-box">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-7">
                    <h5><?php echo e(__('Asset Info')); ?></h5>
                    <div class="asset-info">
                        <div class="asset-info-det">
                            <h6><?php echo e($asset->name); ?></h6>
                            <p><?php echo e($asset->model); ?></p>
                            <ul>
                                <li>Type <span>Keybaord</span></li>
                                <li><?php echo e(__('Serial Number')); ?> <span><?php echo e($asset->serial_no); ?></span></li>
                                <li><?php echo e(__('Brand')); ?> <span><?php echo e($asset->brand); ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if(!empty($asset->files)): ?>    
                    <div class="assets-image">
                        <h5><?php echo e(__('Asset Files')); ?></h5>
                        <ul>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $asset->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if(is_string($file)): ?>
                            <li>
                                <img src="<?php echo e(uploadedAsset($file,'assets')); ?>" width="100px" height="100px" alt="Keyboard Image">
                            </li>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]--> 
                        </ul>
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-lg-5">
                    <div class="asset-history">
                        <h5><?php echo e(__('Asset Info')); ?></h5>
                        <ul>
                            <li>
                                <div class="aset-img">
                                    <img src="<?php echo e(asset('images/icons/icon-01.svg')); ?>" alt="Asset Image">
                                </div>
                                <div class="asset-inf">
                                    <h6><?php echo e(__('Supplier')); ?></h6>
                                    <p><?php echo e($asset->supplier); ?></p>
                                </div>
                            </li>
                            <li>
                                <div class="aset-img">
                                    <img src="<?php echo e(asset('images/icons/icon-04.svg')); ?>" alt="Asset Image">
                                </div>
                                <div class="asset-inf">
                                    <h6><?php echo e(__('Cost')); ?></h6>
                                    <p><?php echo e(LocaleSettings('currency_symbol').$asset->cost); ?></p>
                                </div>
                            </li>
                            <li>
                                <div class="aset-img">
                                    <img src="<?php echo e(asset('images/icons/icon-05.svg')); ?>" alt="Asset Image">
                                </div>
                                <div class="asset-inf">
                                    <h6><?php echo e(('Manufacturer')); ?></h6>
                                    <p><?php echo e($asset->manufacturer); ?></p>
                                </div>
                            </li>
                            <li>
                                <div class="aset-img">
                                    <img src="<?php echo e(asset('images/icons/icon-02.svg')); ?>" alt="Asset Image">
                                </div>
                                <div class="asset-inf">
                                    <h6><?php echo e(__('Warranty')); ?></h6>
                                    <p><span><?php echo e(__('Months: ')); ?></span><?php echo e($asset->warranty); ?></p>
                                    <p><span><?php echo e(__('Ends On: ')); ?></span><?php echo e(format_date($asset->warranty_end)); ?></p>
                                </div>
                            </li>									
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="raise-issue" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo e(__('Raise Issue')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="raiseIssue(<?php echo e($asset->id); ?>)" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-block mb-3">
                                    <label class="col-form-label"><?php echo e(__('Description')); ?></label>
                                    <textarea rows="4" class="form-control" wire:model="description" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="submit-section mt-0">
                            <button class="btn btn-primary submit-btn w-100" type="submit"><?php echo e(('Submit')); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php
        $__scriptKey = '3108914628-0';
        ob_start();
    ?>
    <script defer type="module">
        Livewire.on('IssueRaiseSuccess', (param) => {
            modalEl = document.getElementById('raise-issue')
            bootstrap.Modal.getOrCreateInstance(modalEl).hide()
            console.log(param)
            Toastify({
                text: param,
                className: "success",
            }).showToast()
        })
    </script>
        <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?>
</div>
<?php /**PATH /home/renown/public_html/renownsystem.com/resources/views/livewire/employee-asset.blade.php ENDPATH**/ ?>